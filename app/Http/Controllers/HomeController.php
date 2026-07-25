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

        $featuredNoteId = Cache::remember(
            'featured_note_id',
            now()->tomorrow()->startOfDay(),
            function () {
                return Note::inRandomOrder()->value('id');
            },
        );

        $featuredNote = $featuredNoteId ? Note::find($featuredNoteId) : null;

        if ($featuredNote && !Gate::check('view', $featuredNote)) {
            $featuredNote = null;
        }

        return view('home.show', compact(['title', 'teaser', 'notesCount', 'featuredNote']));
    }
}
