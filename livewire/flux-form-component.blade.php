<div class="my-6">
    <form class="space-y-6" wire:submit.prevent="submit">
        @foreach ($fields as $field)
            <x-verbs-flux::input :field="$field"/>
        @endforeach
        
        <flux:button type="submit">{{ data_get($config, 'submit_text') }}</flux:button>
    </form>
</div>
