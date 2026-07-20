<x-layouts::app title="Create Note">
    <form method="POST" action="{{ route('notes.store') }}" class="space-y-4">
        @csrf

        <div>
            <flux:input
                label="Title"
                type="text"
                name="title"
                id="title"
                value="{{ old('title') }}"
            />
        </div>

        <div>
            <flux:textarea label="Body" name="body" id="body" rows="10">
                {{ old('body') }}
            </flux:textarea>
        </div>

        <div class="mt-4 flex gap-2">
            <flux:button size="sm" variant="primary" type="submit">Create Note</flux:button>
            <flux:button size="sm" variant="ghost" as="a" href="{{ route('notes.index') }}">
                Cancel
            </flux:button>
        </div>
    </form>
</x-layouts::app>
