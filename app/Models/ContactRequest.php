<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ContactRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactRequest extends Model
{
    /** @use HasFactory<ContactRequestFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'request_type',
        'status',
        'preferred_language',
        'ip_address',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    #[Scope]
    protected function new(Builder $query): void
    {
        $query->where('status', 'new');
    }

    #[Scope]
    protected function byType(Builder $query, string $type): void
    {
        $query->where('request_type', $type);
    }

    public function markAsContacted(): bool
    {
        return $this->update(['status' => 'contacted']);
    }

    public function markAsCompleted(): bool
    {
        return $this->update(['status' => 'completed']);
    }
}
