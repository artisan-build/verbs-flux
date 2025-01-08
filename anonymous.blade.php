@php use ArtisanBuild\VerbsFlux\States\MeetingState;use Thunk\Verbs\Models\VerbSnapshot; @endphp
<x-verbs-flux::layout>
    <flux:tab.group>
        <flux:tabs>
            @if (VerbSnapshot::where('type', MeetingState::class)->exists())
                <flux:tab name="edit">MeetingUpdated</flux:tab>
            @endif
            <flux:tab name="meeting">MeetingCreated</flux:tab>

        </flux:tabs>

        <flux:tab.panel name="meeting">
            <flux:heading>MeetingCreated</flux:heading>
            <flux:subheading>This form fires the MeetingCreated event</flux:subheading>
            <livewire:event-form :event="\ArtisanBuild\VerbsFlux\Events\MeetingCreated::class"/>
        </flux:tab.panel>
        <flux:tab.panel name="edit">
            <flux:heading>MeetingCreated</flux:heading>
            <flux:subheading>This form fires the MeetingCreated event</flux:subheading>
            <livewire:event-form :event="\ArtisanBuild\VerbsFlux\Events\MeetingUpdated::class" :state="\ArtisanBuild\VerbsFlux\Models\Meeting::first()?->verbs_state()"/>
        </flux:tab.panel>
    </flux:tab.group>
</x-verbs-flux::layout>
