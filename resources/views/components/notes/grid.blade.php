@php
    use App\Models\Note;
    use Illuminate\Pagination\LengthAwarePaginator;
    /** @var LengthAwarePaginator $notes */
@endphp

@props([
    'notes',
])

<div {{ $attributes->class(['grid grid-cols-1 gap-4 lg:grid-cols-2']) }}>
    @forelse ($notes as $note)
        <x-notes.card :note="$note" />
    @empty
        <p>No notes available.</p>
    @endforelse
</div>

<div class="mt-6">
    {{ $notes->links() }}
</div>
