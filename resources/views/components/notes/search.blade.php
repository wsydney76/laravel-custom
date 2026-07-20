<?php

use App\Models\Note;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

new #[Title('Search Notes')] class extends Component {
    use WithPagination;

    #[Url]
    #[Validate('not_regex:/\*/', message: '*" wildcard is not supported.')]
    public string $search = '';

    public function mount()
    {
        $this->validate();
    }

    #[Computed]
    public function notes()
    {
        return Note::query()
            ->latest()
            ->where('title', 'like', "%{$this->search}%")
            ->orWhere('body', 'like', "%{$this->search}%")
            ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->paginate(8);
    }

    public function updateSearch($value)
    {
        $this->resetPage();
    }
};

?>

<div class="space-y-6">
    <flux:input
        label="Search for:"
        type="search"
        autofocus
        wire:model.live.debounce.300ms="search"
        placeholder="Search in title, body, or user..."
        icon="magnifying-glass"
    />

    @if ($search)
        <x-notes.grid :notes="$this->notes" />
    @else
        <p>Enter a search term to begin.</p>
    @endif
</div>
