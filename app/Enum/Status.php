<?php

declare(strict_types=1);

namespace App\Enum;

enum Status: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';
    case APPROVED = 'approved';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::REJECTED => 'Rejected',
            self::APPROVED => 'Approved',
            default => throw new \Exception('Unexpected match value'),
        };
    }
}
