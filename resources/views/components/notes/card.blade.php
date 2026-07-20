@props([
    /**@var\mixed*/'note',
])

<flux:card {{ $attributes->class('space-y-2') }}>
    <div class="flex items-center justify-between">
        <flux:link :href="route('notes.show', $note)">
            {{ $note->title }}
        </flux:link>
        @can('update', $note)
            <flux:button size="xs" variant="filled" :href="route('notes.edit', $note)">
                Edit
            </flux:button>
        @endcan
    </div>

    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
        {{ Str::limit($note->body, 100, preserveWords: true) }}
    </p>

    <x-notes.meta :note="$note" />
</flux:card>
