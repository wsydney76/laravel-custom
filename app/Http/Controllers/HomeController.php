<?php

namespace App\Http\Controllers;

use App\Models\Note;

class HomeController extends Controller
{
    public function show()
    {
        $notesCount = Note::count();
        return view('home', compact('notesCount'));
    }
}
