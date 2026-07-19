<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    public ?int $selectedUserId = null;
    public string $heading = '';
    public string $subheading = '';
    public string $callbackEvent = 'userSelected';

    #[Computed]
    public function users(): Collection
    {
        return User::orderBy('name')->get();
    }

    #[On('select-user')]
    public function open(
        string $heading,
        ?string $subheading = '',
        ?int $selectedUserId = null,
        ?string $callbackEvent = 'userSelected',
    ): void {
        $this->heading = $heading;
        $this->subheading = $subheading;
        $this->selectedUserId = $selectedUserId ?? $this->users->first()?->id;
        $this->callbackEvent = $callbackEvent;

        $this->modal('select-user')->show();
    }

    public function apply(): void
    {
        $this->dispatch($this->callbackEvent, $this->selectedUserId);
        $this->modal('select-user')->close();
    }
};
?>

<div>
    <flux:modal name="select-user" class="min-w-88">
        <flux:heading size="lg">{{ $heading }}</flux:heading>
        <flux:subheading class="mt-1 mb-6">
            {{ $subheading }}
        </flux:subheading>

        <flux:select wire:model="selectedUserId" :label="__('User')">
            @foreach ($this->users as $user)
                <flux:select.option value="{{ $user->id }}">
                    {{ $user->name }}
                </flux:select.option>
            @endforeach
        </flux:select>

        <div class="mt-6 flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" wire:click="apply">
                {{ __('Apply') }}
            </flux:button>
        </div>
    </flux:modal>
</div>
