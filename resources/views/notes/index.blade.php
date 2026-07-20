<x-layouts::app title="Notes">
    <div class="space-y-6">
        @auth
            <div>
                <flux:button size="sm" variant="primary" :href="route('notes.create')">
                    Create note
                </flux:button>
            </div>
        @endauth

        <ul class="space-y-2">
            @forelse ($notes as $note)
                <li>
                    <flux:link :href="route('notes.show', $note)">
                        {{ $note->title }}
                    </flux:link>
                </li>
            @empty
                <li>No notes available.</li>
            @endforelse
        </ul>

        <div>
            {{ $notes->links() }}
        </div>
    </div>
</x-layouts::app>
