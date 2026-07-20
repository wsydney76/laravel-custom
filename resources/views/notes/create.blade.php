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

        <div class="mt-4">
            <flux:button size="sm" variant="primary" type="submit">Create Note</flux:button>
        </div>
    </form>
</x-layouts::app>
