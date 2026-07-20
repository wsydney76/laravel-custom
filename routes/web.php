<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'show'])->name('home');

// Route::resource('notes', NoteController::class)->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::get('notes/my', [NoteController::class, 'my'])->name('notes.my');
});

Route::get('notes', [NoteController::class, 'index'])->name('notes.index');
Route::get('notes/{note}', [NoteController::class, 'show'])->name('notes.show');

Route::get('test/{template}', function (string $template) {
    return view("test.{$template}", ['template' => $template]);
});

require_once __DIR__ . '/settings.php';
