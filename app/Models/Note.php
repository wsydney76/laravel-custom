<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $body
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $url
 * @property-read string $edit_url
 * @property-read string $created_at_formatted
 * @property-read string $description
 */
class Note extends Model
{
    /** @use HasFactory<\Database\Factories\NoteFactory> */
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'body' => ['required', 'string', 'max:4000'],
        ];
    }

    public function getUrlAttribute()
    {
        return route('notes.show', $this);
    }

    public function getEditUrlAttribute()
    {
        return route('notes.edit', $this);
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->setTimezone('Europe/Berlin')->isoFormat('LLL');
    }

    public function getDescriptionAttribute(): string
    {
        $excerpt = Str::limit($this->body, 120, preserveWords: true);
        $author  = $this->user?->name ?? 'Unknown';
        $date    = $this->created_at?->setTimezone('Europe/Berlin')->isoFormat('LL') ?? '';

        return "{$excerpt} — by {$author}, {$date}";
    }
}
