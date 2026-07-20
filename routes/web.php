<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'show'])->name('home');

Route::resource('notes', NoteController::class)->middleware('auth');

Route::get('test/{template}', function (string $template) {
    return view("test.{$template}", ['template' => $template]);
});

require_once __DIR__ . '/settings.php';
