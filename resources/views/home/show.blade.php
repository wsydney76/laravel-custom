@php
    use App\Models\Note;
    /** @var string $title */
    /** @var string $teaser */
    /** @var int $notesCount */
    /** @var Note $featuredNote */
@endphp

<x-layouts::app class="space-y-8" :title="$title">
    <flux:heading size="lg">{{ $teaser }}</flux:heading>

    @if ($featuredNote)
        <x-notes.featured-note :note="$featuredNote" />
    @endif

    @if ($notesCount)
        <flux:button as="a" size="sm" variant="primary" :href="route('notes.index')">
            View {{ $notesCount }} {{ Str::plural('note', $notesCount) }}
        </flux:button>
    @else
        <p>No notes available.</p>
    @endif
</x-layouts::app>
