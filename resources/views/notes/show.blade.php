<x-layouts::app :title="$note->title">
    @if ($note->body)
        <p>{!! nl2br(e($note->body)) !!}</p>
    @else
        <flux:text>No body content provided.</flux:text>
    @endif

    @auth
        <div class="mt-8 flex gap-2">
            <flux:button variant="primary" size="sm" :href="route('notes.edit', $note)">
                Edit
            </flux:button>
            <form
                method="POST"
                action="{{ route('notes.destroy', $note) }}"
                onsubmit="return confirm('Are you sure you want to delete this note?');"
            >
                @csrf
                @method('DELETE')
                <flux:button size="sm" type="submit" variant="danger">Delete</flux:button>
            </form>
        </div>
    @endauth
</x-layouts::app>
