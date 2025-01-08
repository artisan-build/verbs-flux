# Verbs Flux

Turn your [Verbs](https://verbs.thunk.dev) events into [Flux](https://fluxui.dev/) forms.

## Usage

First, install the package: `composer install artisan-build/verbs-flux`

Next, add some attributes to your event to indicate what type of fields should be used:

```diff
namespace App\Events;

use App\States\UserBalance;
+ use ArtisanBuild\VerbsFlux\Attributes\EventForm;
+ use ArtisanBuild\VerbsFlux\Attributes\EventInput;
+ use ArtisanBuild\VerbsFlux\Enums\InputTypes;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

+ #[EventForm()]
class FundsAdded extends Event
{
    #[StateId(UserBalance::class)]
    public int $userId;

+     #[EventInput(
+         type: InputTypes::Number,
+         label: 'Amount',
+         rules: ['required', 'numeric', 'min:0.01'],
+     )]
    public float $amount;

+     #[EventInput(
+         type: InputTypes::Text,
+         label: 'Notes',
+     )]
    public string $notes;
```

Finally, add a livewire component and indicate which event it should use:

```blade
<livewire:event-form :event="\App\Events\FundsAdded::class" />
```

Tip: you can include this inside a modal like this:

```blade
<flux:modal.trigger name="adjust-balance">
    <flux:button>Add Transaction</flux:button>
</flux:modal.trigger>

<flux:modal name="adjust-balance" class="md:w-96 space-y-6">
    <livewire:event-form :event="\App\Events\FundsAdded::class" />
</flux:modal>
```
