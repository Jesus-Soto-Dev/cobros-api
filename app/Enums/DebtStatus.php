<?php

namespace App\Enums;

enum DebtStatus: string
{
    case Pending = 'pendiente';
    case InProgress = 'en_gestion';
    case Paid = 'pagada';
    case Overdue = 'vencida';
    case Uncollectible = 'incobrable';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::InProgress => 'En gestión',
            self::Paid => 'Pagada',
            self::Overdue => 'Vencida',
            self::Uncollectible => 'Incobrable',
        };

    }
}
