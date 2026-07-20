@props([
    /**@var\App\Models\Note*/'note',
])

<flux:text size="sm" {{ $attributes }}>
    {{ $note->user?->name ?? 'Unknown user' }}
    &middot;
    {{ $note->created_at_formatted }}
</flux:text>
