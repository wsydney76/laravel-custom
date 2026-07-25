<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function show()
    {
        $title = config('app.name');
        $teaser = Inspiring::quotes()->random();
        $notesCount = Note::count();

        $featuredNoteId = Cache::remember('featured_note_id', now()->addDay(), function () {
            return Note::inRandomOrder()->value('id');
        });

        $featuredNote = $featuredNoteId ? Note::find($featuredNoteId) : null;

        return view('home', compact(['title', 'teaser', 'notesCount', 'featuredNote']));
    }
}
