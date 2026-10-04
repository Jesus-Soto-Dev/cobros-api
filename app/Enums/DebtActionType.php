<?php

namespace App\Enums;

enum DebtActionType: string
{
    case Call = 'llamada';
    case Email = 'email';
    case Sms = 'sms';
    case PaymentPromise = 'promesa_pago';
    case Visit = 'visita';

    public function label(): string
    {
        return match ($this) {
            self::Call => 'Llamada',
            self::Email => 'Email',
            self::Sms => 'SMS',
            self::PaymentPromise => 'Promesa de pago',
            self::Visit => 'Visita',
        };
    }
}
