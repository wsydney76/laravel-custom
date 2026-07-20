@props([
    /**@var\App\Models\Note*/'note',
])

<flux:text size="sm" {{ $attributes }}>
    {{ $note->user?->name ?? 'Unknown user' }}
    &middot;
    {{ $note->created_at->setTimezone('Europe/Berlin')->format('d.m.Y H:i') }}
</flux:text>
