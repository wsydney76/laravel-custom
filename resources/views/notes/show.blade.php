<x-layouts::app :title="$note->title">
    @if ($note->body)
        <p>{!! nl2br(e($note->body)) !!}</p>
    @else
        <flux:text>No body content provided.</flux:text>
    @endif

    <x-notes.meta class="mt-6" :note="$note" />

    @can('update', $note)
        <x-slot name="title_actions">
            <div class="flex gap-2">
                <flux:button variant="primary" size="sm" :href="route('notes.edit', $note)">
                    Edit
                </flux:button>

                @can('delete', $note)
                    <form
                        method="POST"
                        :action="route('notes.destroy', $note)"
                        onsubmit="return confirm('Are you sure you want to delete this note?');"
                    >
                        @csrf
                        @method('DELETE')
                        <flux:button type="submit" variant="danger" size="sm">Delete</flux:button>
                    </form>
                @endcan
            </div>
        </x-slot>
    @endcan
</x-layouts::app>
