<?php

use App\Models\Note;
use App\Models\User;

it('returns a successful response', function () {
    $this->get(route('home'))->assertOk();
});

it('resolves correct title', function () {
    $response = $this->get(route('home'));
    $response->assertViewHas('title', config('app.name'));
});

it('passes correct notes count to view, acting as guest', function () {
    Note::factory()->count(5)->create();

    $response = $this->get(route('home'));
    $response->assertViewHas('notesCount', 5);
});

it('passes correct notes count to view, acting as user', function () {
    Note::factory()->count(5)->create();
    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)->get(route('home'));
    $response->assertViewHas('notesCount', 5);
});

it('shows warning if there are no notes', function () {
    $response = $this->get(route('home'));
    $response->assertSee('No notes available.');
});

it('shows correct button caption', function () {
    Note::factory()->count(5)->create();

    $response = $this->get(route('home'));
    $response->assertSee('View 5 notes');
});

it('resolves a featured note as guest', function () {
    $note = Note::factory()->create();

    $response = $this->get(route('home'));
    $response->assertViewHas('featuredNote', function ($viewNote) use ($note) {
        return $viewNote instanceof Note && $viewNote->title === $note->title;
    });
});

it('resolves a featured note as user', function () {
    $note = Note::factory()->create();
    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)->get(route('home'));
    $response->assertViewHas('featuredNote', function ($viewNote) use ($note) {
        return $viewNote instanceof Note && $viewNote->title === $note->title;
    });
});

it('refreshes a featured note after deletion', function () {
    $note = Note::factory()->create(['title' => 'The first note']);

    $response = $this->get(route('home'));
    $response->assertViewHas('featuredNote', function ($viewNote) {
        return $viewNote instanceof Note && $viewNote->title === 'The first note';
    });

    $note->delete();
    Note::factory()->create(['title' => 'The second note']);

    $response = $this->get(route('home'));
    $response->assertViewHas('featuredNote', function ($viewNote) {
        return $viewNote instanceof Note && $viewNote->title === 'The second note';
    });
});
