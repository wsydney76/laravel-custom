<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    public function show()
    {
        $title = config('app.name');
        $teaser = Inspiring::quotes()->random();
        $notesCount = Note::count();
        $featuredNote = $notesCount ? $this->getFeaturedNote() : null;

        return view('home.show', compact(['title', 'teaser', 'notesCount', 'featuredNote']));
    }

    /** Get a random note for the current calendar day */
    protected function getFeaturedNote(): ?Note
    {
        $ttl = now()->tomorrow()->startOfDay();

        $noteId = Cache::remember('featured_note_id', $ttl, fn() => Note::getFeaturedNote()?->id);
        $note = $noteId ? Note::find($noteId) : null;

        // If the cached note was deleted, pick a new one
        if ($noteId && !$note) {
            $note = Note::getFeaturedNote();
            Cache::put('featured_note_id', $note?->id, $ttl);
        }

        return $note && Gate::check('view', $note) ? $note : null;
    }
}
