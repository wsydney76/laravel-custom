<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use function abort_unless;
use function dd;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::latest()->paginate(10);

        return view('notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(auth()->check(), 403);

        $data = $request->validate([
            'title' => 'required',
            'body' => 'nullable',
        ]);

        $note = Note::create($data);
        return redirect()->route('notes.show', $note)->with('status', 'Note created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        abort_unless(auth()->check(), 403);

        $data = $request->validate([
            'title' => 'required',
            'body' => 'nullable',
        ]);

        $note->update($data);
        return redirect()->route('notes.show', $note)->with('status', 'Note updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        abort_unless(auth()->check(), 403);

        $note->delete();
        return redirect()->route('notes.index')->with('status', 'Note deleted successfully');
    }
}
