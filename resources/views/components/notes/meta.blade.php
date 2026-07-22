@php
    use App\Models\Note;
    /** @var Note $note */
@endphp

@props([
    'note',
])
<flux:text size="sm" {{ $attributes }}>
    {{ $note->user?->name ?? 'Unknown user' }}
    &middot;
    {{ $note->created_at_formatted }}
</flux:text>
