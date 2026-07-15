<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Support\Facades\Gate;

class UpdateArticleRequest extends StoreArticleRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('article'));
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'delete_featured_image' => ['nullable', 'boolean'],
        ]);
    }
}

