<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AlbumFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Model
{
    /** @use HasFactory<AlbumFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'description',
        'cover_image_path',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Album::class, 'parent_id')->orderBy('sort_order');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class)->orderBy('sort_order');
    }

    public function publishedImages(): HasMany
    {
        return $this->images()->where('status', 'published');
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_published', true);
    }

    #[Scope]
    protected function withPublishedImages(Builder $query): void
    {
        $query->whereHas('images', fn ($q) => $q->where('status', 'published'));
    }

    #[\Override]
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
