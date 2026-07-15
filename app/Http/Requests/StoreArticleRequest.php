<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'min:10', 'max:255'],
            'body'           => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
        ];
    }
}

