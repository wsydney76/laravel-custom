<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::with('user')->latest()->paginate(8);

        return view('articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('articles.create');
    }

    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $article = $request->user()->articles()->create($request->validated());

        if ($request->hasFile('featured_image')) {
            $article->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        }

        return redirect()
            ->route('articles.show', $article)
            ->with('status', __('Article created successfully.'));
    }

    public function show(Article $article): View
    {
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article): View
    {
        Gate::authorize('update', $article);

        return view('articles.edit', compact('article'));
    }

    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $article->update($request->validated());

        if ($request->hasFile('featured_image')) {
            $article->clearMediaCollection('featured_image');
            $article->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        } elseif ($request->boolean('delete_featured_image')) {
            $article->clearMediaCollection('featured_image');
        }

        return redirect()
            ->route('articles.show', $article)
            ->with('status', __('Article updated successfully.'));
    }

    public function destroy(Article $article): RedirectResponse
    {
        Gate::authorize('delete', $article);

        $article->delete();

        return redirect()->route('articles.index')->with('status', __('Article deleted.'));
    }
}
