@php
    use App\Models\Note;
    /** @var string $title */
    /** @var string $teaser */
    /** @var int $notesCount */
    /** @var Note $featuredNote */
@endphp

<x-layouts::app :title="$title">
    <flux:heading size="lg">{{ $teaser }}</flux:heading>

    @if ($featuredNote)
        <flux:card class="mt-8 space-y-4">
            <flux:heading size="lg">Today's Featured Note:</flux:heading>
            <flux:heading size="lg">{{ $featuredNote->title }}</flux:heading>

            @if ($featuredNote->body)
                <p>
                    <x-nl2br :text="Str::limit($featuredNote->body, 150, preserveWords: true)" />
                </p>

                <p>
                    <flux:link :href="route('notes.show', $featuredNote)">Read More</flux:link>
                </p>
            @endif

            <x-notes.meta :note="$featuredNote" />
        </flux:card>
    @endif

    <div class="mt-8">
        @if ($notesCount)
            <flux:button as="a" size="sm" variant="primary" :href="route('notes.index')">
                View {{ $notesCount }} {{ Str::plural('note', $notesCount) }}
            </flux:button>
        @else
            <p>No notes available.</p>
        @endif
    </div>
</x-layouts::app>
