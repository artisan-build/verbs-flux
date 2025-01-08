@props(['event', 'event_data' => [], 'state' => null, 'variant' => 'flyout', 'button_text' => 'Open Form', 'heading' => null, 'subheading' => null, 'close' => null])

@php $name = \Illuminate\Support\Str::of(last(explode( '\\', $event)))->headline()->slug(); @endphp

@if ($variant === 'flyout')
    <flux:modal.trigger :name="$name">
        <flux:button>{{ $button_text }}</flux:button>
    </flux:modal.trigger>

    <flux:modal :name="$name" variant="flyout" class="space-y-6" :wire:close="$close">
        <div>
            @if ($heading !== null)
                <flux:heading size="lg">{{ $heading }}</flux:heading>
            @endif
            @if ($subheading !== null)
                <flux:subheading>{{ $subheading }}</flux:subheading>
            @endif
        </div>

        <livewire:event-form :event="$event" :state="$state" :event_data="$event_data"/>

    </flux:modal>
@endif
