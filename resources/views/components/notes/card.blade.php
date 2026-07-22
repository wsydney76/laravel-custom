@php
    use App\Models\Note;
    /** @var Note $note */
@endphp

@props([
    'note',
])

<flux:card {{ $attributes->class('space-y-2') }}>
    <div class="flex items-center justify-between">
        <flux:link :href="$note->url">
            {{ $note->title }}
        </flux:link>
        @can('update', $note)
            <flux:button size="xs" variant="filled" :href="$note->edit_url">Edit</flux:button>
        @endcan
    </div>

    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
        {{ Str::limit($note->body, 100, preserveWords: true) }}
    </p>

    <x-notes.meta :note="$note" />
</flux:card>
