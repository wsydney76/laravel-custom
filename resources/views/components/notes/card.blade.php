@props([
    /**@var\mixed*/'note',
])

<flux:card {{ $attributes->class('space-y-2') }}>
    <div>
        <flux:link :href="route('notes.show', $note)">
            {{ $note->title }}
        </flux:link>
    </div>

    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
        {{ Str::limit($note->body, 100, preserveWords: true) }}
    </p>

    <x-notes.meta :note="$note" />
</flux:card>
