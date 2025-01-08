@use('ArtisanBuild\VerbsFlux\Enums\InputTypes')
@use('Illuminate\View\ComponentAttributeBag')
@props(['field' => []])
@php extract($field) @endphp

<flux:field>
    <flux:label :badge="$badge">{{ $label ?? \Illuminate\Support\Str::headline($name) }}</flux:label>

    @if ($description && !$description_trailing)
        <flux:description>{{ $description }}</flux:description>
    @endif

    <flux:input.group>
        @if ($prefix)
            <flux:input.group.prefix>{{ $prefix }}</flux:input.group.prefix>
        @endif

        @if ($type === InputTypes::Textarea)
            <flux:textarea
                rows="{{$rows}}"
                wire:model="data.{{ $name }}"
                :attributes="new ComponentAttributeBag($attributes)"
            />
        @elseif ($type === InputTypes::Select)
            <flux:select
                wire:model="data.{{ $name }}"
                :attributes="new ComponentAttributeBag($attributes)"
            >
                <x-flux::option>--- Choose One ---</x-flux::option>
                @foreach ($options as $key => $value)
                    <x-flux::option :value="$key">{{ $value }}</x-flux::option>
               @endforeach
            </flux:select>
        @else
            <flux:input
                type="{{ $type->value }}"
                wire:model="data.{{ $name }}"
                :attributes="new ComponentAttributeBag($attributes)"
            />
        @endif

        @if ($suffix)
            <flux:input.group.suffix>{{ $suffix }}</flux:input.group.suffix>
        @endif
    </flux:input.group>

    @if ($description && $description_trailing)
        <flux:description>{{ $description }}</flux:description>
    @endif

    <flux:error name="data.{{ $name }}"/>
</flux:field>
