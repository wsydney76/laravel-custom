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

    /**
     * Get a random note for the current calendar day
     *
     * @return Note|null
     */
    protected function getFeaturedNote(): ?Note
    {
        // Cache a note id for the current day
        $cacheKey = 'featured_note_id';
        $ttl = now()->tomorrow()->startOfDay();

        $featuredNoteId = Cache::remember($cacheKey, $ttl, function () {
            return Note::inRandomOrder()->value('id');
        });

        $featuredNote = Note::find($featuredNoteId);

        // Featured note obviously deleted, replace with a new one
        if ($featuredNoteId && !$featuredNote) {
            $featuredNote = Note::inRandomOrder()->first();
            Cache::put($cacheKey, $featuredNote->id, $ttl);
        }

        // Don't show note if user is not permitted to view
        if (!Gate::check('view', $featuredNote)) {
            $featuredNote = null;
        }
        return $featuredNote;
    }
}
