@php
    use App\Models\Note;
    /** @var Note $note */
@endphp

@props([
    'note',
])

<flux:card {{
    $attributes->class([
        'space-y-2',
    ])
}}>
    <div class="flex items-center justify-between gap-1">
        <flux:link :href="$note->url">
            {{ $note->title }}
        </flux:link>

        @can('update', $note)
            <flux:button size="xs" variant="filled" :href="$note->edit_url">Edit</flux:button>
        @endcan
    </div>

    <flux:text>
        {{ Str::limit($note->body, 100, preserveWords: true) }}
    </flux:text>

    <x-notes.meta :$note />
</flux:card>
