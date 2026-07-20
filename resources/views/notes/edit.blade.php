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
            <flux:error name="body" />
        </div>

        <div class="mt-4 flex gap-2">
            <flux:button size="sm" variant="primary" type="submit">Update Note</flux:button>
            <flux:button size="sm" variant="ghost" as="a" href="{{ $note->url }}">
                Cancel
            </flux:button>
        </div>
    </form>
</x-layouts::app>
