<?php

declare(strict_types=1);

namespace ArtisanBuild\VerbsFlux\Livewire;

use ArtisanBuild\VerbsFlux\Attributes\EventForm;
use ArtisanBuild\VerbsFlux\Attributes\EventInput;
use ArtisanBuild\VerbsFlux\Contracts\RedirectsOnSuccess;
use ArtisanBuild\VerbsFlux\Enums\InputTypes;
use BackedEnum;
use Flux\Flux;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Livewire\Component;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use RuntimeException;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;
use Thunk\Verbs\State;

class FluxFormComponent extends Component
{
    public array $config = [];

    public array $fields = [];

    /**
     * @var class-string<Event>|null
     */
    public ?string $event = null;

    public ?string $state_key = null;

    public array $data = [];

    public array $rules = [];

    public function mount(
        string $event,
        $state = null,
        $event_data = [],
    ): void {
        $this->event ??= $event;
        $this->data = $event_data;

        $reflection = new ReflectionClass($this->event);

        $config = $reflection->getAttributes(EventForm::class)[0]->getArguments();
        $default = (array) $reflection->getAttributes(EventForm::class)[0]->newInstance();

        $this->config = array_merge($config, $default);

        $this->state_key = collect($reflection->getProperties())
            ->filter(fn ($property) => ! empty($property->getAttributes(StateId::class)))
            ->first()->getName();

        $this->rules['data.'.$this->state_key] = ['nullable'];

        collect($reflection->getProperties())
            ->filter(fn ($property) => ! empty($property->getAttributes(EventInput::class)))
            ->each(function ($property) use ($state): void {
                $default = (array) $property->getAttributes(EventInput::class)[0]->newInstance();
                $data = $property->getAttributes(EventInput::class)[0]->getArguments();
                $data['name'] = $property->getName();
                $this->data[$data['name']] = match (true) {
                    $data['type'] === InputTypes::DatetimeLocal => $state?->{$data['name']}?->format('Y-m-d\TH:i'),
                    default => $state?->{$data['name']},
                };
                if ($state instanceof State) {
                    $this->data[$this->state_key] = data_get($state, 'id');
                }

                $required = ! $property->getType()?->allowsNull();
                if ($required && ! data_get($data, 'badge')) {
                    $data['badge'] = 'required';
                    $data['params']['required'] = 'required';
                }

                $this->rules['data.'.$data['name']] = data_get($data, 'rules', []);

                $data = $this->transform($default, $data);

                $data['attributes'] = $this->prepareAttributes(data_get($data, 'type'), data_get($data, 'params', []));

                $this->fields[] = $data;
            });
    }

    public function transform(array $default, array $data): array
    {
        $data = array_merge($default, $data);

        if (
            $data['type'] === InputTypes::DatetimeLocal
            && isset($data['params']['min'])
        ) {
            $min = $this->datetime_minmax_parser($data['params']['min']);
            $datetime = match ($min['unit']) {
                'now' => Date::now(),
                'days' => Date::now()->addDays($min['value']),
                'weeks' => Date::now()->addWeeks($min['value']),
                'months' => Date::now()->addMonths($min['value']),
                'years' => Date::now()->addYears($min['value']),
                default => throw new RuntimeException('Invalid datetime min unit'),
            };
            $data['params']['min'] = $datetime->format('Y-m-d\TH:i');
        }

        if (
            $data['type'] === InputTypes::DatetimeLocal
            && isset($data['params']['max'])
        ) {
            $max = $this->datetime_minmax_parser($data['params']['max']);
            $datetime = match ($max['unit']) {
                'now' => Date::now(),
                'days' => Date::now()->addDays($max['value']),
                'weeks' => Date::now()->addWeeks($max['value']),
                'months' => Date::now()->addMonths($max['value']),
                'years' => Date::now()->addYears($max['value']),
                default => throw new RuntimeException('Invalid datetime max unit'),
            };
            $data['params']['max'] = $datetime->format('Y-m-d\TH:i');
        }

        if (
            $data['type'] === InputTypes::Select
            && is_string($data['options'])
            && assert(class_exists($data['options']))
            && assert(is_subclass_of($data['options'], BackedEnum::class))
        ) {
            $enum = $data['options'];
            $data['options'] = collect($enum::cases())
                ->filter(function (BackedEnum $case) use ($enum, $data) {
                    $filter = $data['options_filter'];

                    if ($filter === null) {
                        return true;
                    }

                    throw_if(
                        condition: ! method_exists($enum, $filter),
                        exception: RuntimeException::class,
                        parameters: "Enum {$enum} does not contain a {$filter} method.",
                    );

                    // TODO: Should I use Reflection to ensure that this method returns a boolean?
                    return $case->{$filter}();
                })
                ->mapWithKeys(fn (BackedEnum $case) => [$case->value => $case->name]);
        }

        return $data;
    }

    public function submit(): void
    {
        $this->validate($this->rules);

        $reflection = new ReflectionClass($this->event);
        collect($reflection->getProperties())
            ->filter(fn (ReflectionProperty $property): bool => ! empty($property->getAttributes(EventInput::class)))
            ->each(function (ReflectionProperty $property): void {
                $type = $property->getType();
                assert($type instanceof ReflectionNamedType);

                $cast = match ($type->getName()) {
                    'int' => intval(...),
                    'bool' => boolval(...),
                    'string' => strval(...),
                    'float' => floatval(...),
                    default => fn ($v) => $v,
                };

                $mutate = fn (mixed &$v, callable $callable): mixed => $v = $callable($v);

                $mutate($this->data[$property->getName()], $cast);
                // (fn (&$v) => $v = $cast($v))($this->data[$property->getName()]);
                // $this->data[$property->getName()] = $cast($this->data[$property->getName()]);
            });

        $success = $this->event::commit($this->data);

        // @phpstan-ignore-next-line
        Flux::toast(text: data_get($this->config, 'success'), variant: 'success');
        $this->dispatch('saved');
        // @phpstan-ignore-next-line
        Flux::modals()->close();

        if (data_get($this->config, 'on_success') === RedirectsOnSuccess::class) {
            $url = App::make(RedirectsOnSuccess::class)($this->event, $success);
            if ($url !== null) {
                $this->redirect(url: $url, navigate: true);
            }
        }
    }

    public function prepareAttributes(InputTypes $type, array $params): array
    {
        $filtered = [];

        // First we will filter out anything that isn't allowed to be passed through
        foreach ($params as $key => $value) {
            // Allow ARIA attributes
            if (Str::of($key)->startsWith('aria-')) {
                $filtered[$key] = $value;
            }
            // Allow all data attributes
            if (Str::of($key)->startsWith('data-')) {
                $filtered[$key] = $value;
            }
            // Allow all Alpine
            if (Str::of($key)->startsWith('x-')) {
                $filtered[$key] = $value;
            }
            // Allow all Livewire
            if (Str::of($key)->startsWith('wire:')) {
                $filtered[$key] = $value;
            }
            // Allow type-specific attributes
            if (in_array($key, $type->allowed(), true)) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    public function render()
    {
        return View::make('verbs-flux::livewire.flux-form-component');
    }

    /**
     * @return array{unit: string, value: int}
     */
    private function datetime_minmax_parser(string $input): array
    {
        $exploded = explode(':', $input);
        $unit = $exploded[0];
        $value = (int) ($exploded[1] ?? 0);

        if ($unit === 'now' && $value > 0) {
            throw new RuntimeException('Cannot use "now" with a value greater than 0');
        }

        return ['unit' => $unit, 'value' => $value];
    }
}
