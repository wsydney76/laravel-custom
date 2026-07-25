@props([
    /**@var\App\Models\Note*/'note',
])

<flux:card {{ $attributes->class(['space-y-4']) }}>
    <flux:heading size="lg">Today's Featured Note:</flux:heading>
    <flux:heading size="lg">{{ $note->title }}</flux:heading>

    @if ($note->body)
        <p>
            <x-nl2br :text="Str::limit($note->body, 150, preserveWords: true)" />
        </p>

        <p>
            <flux:link :href="route('notes.show', $note)">Read More</flux:link>
        </p>
    @endif

    <x-notes.meta :$note />
</flux:card>
