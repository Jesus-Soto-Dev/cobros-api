<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'efectivo';
    case Transfer = 'transferencia';
    case Card = 'tarjeta';
    case Other = 'otro';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Efectivo',
            self::Transfer => 'Transferencia',
            self::Card => 'Tarjeta',
            self::Other => 'Otro',
        };
    }
}