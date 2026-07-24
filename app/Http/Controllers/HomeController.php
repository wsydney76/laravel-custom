<?php

namespace App\Http\Controllers;

use App\Models\Note;

class HomeController extends Controller
{
    public function show()
    {
        $title = config('app.name');
        $teaser = fake()->sentence(12);
        $notesCount = Note::count();

        $featuredNote = Note::inRandomOrder()->first();

        return view('home', compact(['title', 'teaser', 'notesCount', 'featuredNote']));
    }
}
