<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Note::class);

        $notes = Note::with('user')->latest()->paginate(8);
        $title = 'Notes';

        return view('notes.index', compact('notes', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Note::class);

        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Note::class);

        $data = $request->validate(Note::rules());

        $note = auth()->user()->notes()->create($data);
        return redirect()->to($note->url)->with('status', 'Note created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        $this->authorize('view', $note);

        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        $this->authorize('update', $note);

        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        $data = $request->validate(Note::rules());

        $note->update($data);
        return redirect()->to($note->url)->with('status', 'Note updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);

        $note->delete();
        return redirect()->route('notes.index')->with('status', 'Note deleted successfully');
    }

    public function my()
    {
        $this->authorize('viewAny', Note::class);

        $notes = auth()->user()->notes()->latest()->paginate(8);
        $title = 'My Notes';
        return view('notes.index', compact('notes', 'title'));
    }
}
