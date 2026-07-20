@php
    use App\Models\Note;
@endphp

<x-layouts::app title="Notes">
    <div class="space-y-6">
        <div class="space-y-2">
            @forelse ($notes as $note)
                <x-notes.card :note="$note" />
            @empty
                <p>No notes available.</p>
            @endforelse
        </div>

        <div>
            {{ $notes->links() }}
        </div>
    </div>

    <x-slot name="titleactions">
        @can('create', Note::class)
            <div>
                <flux:button size="sm" variant="primary" :href="route('notes.create')">
                    Create note
                </flux:button>
            </div>
        @endcan
    </x-slot>
</x-layouts::app>
