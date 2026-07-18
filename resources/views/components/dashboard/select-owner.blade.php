@props([
    'users',
    'heading' => __('Change Owner'),
    'subheading' => __('Select a new owner'),
])

<flux:modal name="change-owner" {{ $attributes->class(['min-w-88']) }}>
    <flux:heading size="lg">{{ $heading }}</flux:heading>
    <flux:subheading class="mt-1 mb-6">
        {{ $subheading }}
    </flux:subheading>

    <flux:select wire:model="changeOwnerUserId" :label="__('Owner')">
        @foreach ($users as $user)
            <flux:select.option value="{{ $user->id }}">
                {{ $user->name }}
            </flux:select.option>
        @endforeach
    </flux:select>

    <div class="mt-6 flex justify-end gap-2">
        <flux:modal.close>
            <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
        </flux:modal.close>
        <flux:button variant="primary" wire:click="applyChangeOwner">
            {{ __('Apply') }}
        </flux:button>
    </div>
</flux:modal>
