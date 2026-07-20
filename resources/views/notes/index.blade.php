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
            @foreach ($notes as $note)
                <li>
                    <flux:link :href="route('notes.show', $note)">
                        {{ $note->title }}
                    </flux:link>
                </li>
            @endforeach
        </ul>

        <div>
            {{ $notes->links() }}
        </div>
    </div>
</x-layouts::app>
