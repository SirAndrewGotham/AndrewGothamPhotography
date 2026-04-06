<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class)->withTimestamps();
    }

    #[\Override]
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
