<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ImageFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    /** @use HasFactory<ImageFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'album_id',
        'title',
        'description',
        'file_name',
        'mime_type',
        'file_size',
        'disk',
        'width',
        'height',
        'is_watermarked',
        'status',
        'sort_order',
        'metadata',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'is_watermarked' => 'boolean',
        'sort_order' => 'integer',
        'metadata' => 'array',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function approvedComments(): MorphMany
    {
        return $this->comments()->where('is_approved', true);
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('status', 'published');
    }

    #[Scope]
    protected function withTags(Builder $query, array $tagSlugs): void
    {
        $query->whereHas('tags', fn ($q) => $q->whereIn('slug', $tagSlugs));
    }

    public function getUrl(string $conversion = 'original'): string
    {
        // Integration point for Spatie Media Library or Yandex Cloud
        return asset("storage/{$this->disk}/{$this->file_name}");
    }

    public function getDimensionsAttribute(): ?string
    {
        return $this->width && $this->height
            ? "{$this->width}×{$this->height}"
            : null;
    }
}
