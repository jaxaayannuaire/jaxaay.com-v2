<?php

namespace App\Enums;

enum BillingCycle: string
{
    case Monthly = 'monthly';
    case Yearly = 'yearly';

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
