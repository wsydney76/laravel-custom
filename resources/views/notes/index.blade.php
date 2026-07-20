@php
    use App\Models\Note;
@endphp

<x-layouts::app :$title>
    <x-notes.grid :$notes />

    <x-slot name="title_actions">
        @can('create', Note::class)
            <div>
                <flux:button size="sm" variant="primary" :href="route('notes.create')">
                    Create note
                </flux:button>
            </div>
        @endcan
    </x-slot>
</x-layouts::app>
