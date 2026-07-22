{{-- @var int $notesCount --}}
<x-layouts::app>
    @if ($notesCount)
        <flux:button as="a" size="sm" variant="primary" href="{{ route('notes.index') }}">
            View {{ $notesCount }} notes
        </flux:button>
    @else
        <p>No notes available.</p>
    @endif
</x-layouts::app>
