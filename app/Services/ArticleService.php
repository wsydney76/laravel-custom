<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use App\Notifications\ArticleCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleService
{
    public function resolveSlug(?string $slug, string $title, ?int $ignoreId = null): string
    {
        $base = $slug ? Str::slug($slug) : Str::slug($title);
        $candidate = $base;
        $i = 1;

        while (
            Article::where('slug', $candidate)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $candidate = $base . '-' . $i++;
        }

        return $candidate;
    }

    public function create(User $author, array $data, ?Request $request = null): Article
    {
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['title']);
        $data['creator_id'] = $author->id;

        $article = $author->articles()->create($data);

        if ($request?->hasFile('featured_image')) {
            $article->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        }

        $this->notifyAdmins($article);

        return $article;
    }

    public function update(Article $article, array $data, ?Request $request = null): Article
    {
        $data['slug'] = $this->resolveSlug($data['slug'] ?? null, $data['title'], $article->id);

        $article->update($data);

        if ($request?->hasFile('featured_image')) {
            $article->clearMediaCollection('featured_image');
            $article->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        } elseif ($request?->boolean('delete_featured_image')) {
            $article->clearMediaCollection('featured_image');
        }

        return $article;
    }

    public function delete(Article $article): void
    {
        $article->delete();
    }

    public function changeState(Article $article, string $state): void
    {
        $article->state = $state;
        $article->save();
    }

    public function changeOwner(Article $article, int $newOwnerId): void
    {
        $article->user_id = $newOwnerId;
        $article->save();
    }

    public function reassignArticles(User $fromUser, int $toUserId): void
    {
        $fromUser->articles()->update(['user_id' => $toUserId]);
    }

    public function notifyAdmins(Article $article): void
    {
        $admins = User::query()
            ->where('role', 'admin')
            ->where('id', '!=', $article->creator_id ?? auth()->id())
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new ArticleCreated($article));
        }
    }
}
