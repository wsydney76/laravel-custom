<x-layouts::app title="Edit Note">
    <form method="POST" action="{{ route('notes.update', $note) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <flux:input
                label="Title"
                type="text"
                name="title"
                id="title"
                value="{{ old('title', $note->title) }}"
            />
        </div>

        <div>
            <flux:textarea label="Body" name="body" id="body" rows="10">
                {{ old('body', $note->body) }}
            </flux:textarea>
        </div>

        <div class="mt-4">
            <flux:button size="sm" variant="primary" type="submit">Update Note</flux:button>
        </div>
    </form>
</x-layouts::app>
