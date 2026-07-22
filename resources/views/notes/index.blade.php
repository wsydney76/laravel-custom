@php
    use App\Models\Note;
    use Illuminate\Pagination\LengthAwarePaginator;
    /** @var LengthAwarePaginator $notes */
    /** @var string $title */
@endphp

<x-layouts::app :$title>
    <x-notes.grid :$notes />

    <x-slot name="title_actions">
        <div class="flex gap-2">
            <flux:button as="a" size="sm" :href="route('notes.search')">Search notes</flux:button>
            @can('create', Note::class)
                <div>
                    <flux:button size="sm" variant="primary" :href="route('notes.create')">
                        Create note
                    </flux:button>
                </div>
            @endcan
        </div>
    </x-slot>
</x-layouts::app>
