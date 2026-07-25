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
        $featuredNote = null;

        // Only resolve a featured note if there are notes in the database
        // Queries inside of this conditional will therefore always find a note,
        // no double-checking needed.
        if ($notesCount) {
            // Cache a note id for the current day
            $featuredNoteId = Cache::remember(
                'featured_note_id',
                now()->tomorrow()->startOfDay(),
                function () {
                    return Note::inRandomOrder()->value('id');
                },
            );

            $featuredNote = Note::find($featuredNoteId);

            // Featured note obviously deleted, replace with a new one
            if ($featuredNoteId && !$featuredNote) {
                $featuredNote = Note::inRandomOrder()->first();
                Cache::put('featured_note_id', $featuredNote->id, now()->tomorrow()->startOfDay());
            }

            // Don't show note if user is not permitted to view
            if (!Gate::check('view', $featuredNote)) {
                $featuredNote = null;
            }
        }

        return view('home.show', compact(['title', 'teaser', 'notesCount', 'featuredNote']));
    }
}
