<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case Pending = 'pending';
    case Trialing = 'trialing';
    case Active = 'active';
    case Grace = 'grace';
    case Cancelled = 'cancelled';
    case Expired = 'expired';

    /** @return list<string> */
    public static function currentValues(): array
    {
        return [
            self::Pending->value,
            self::Trialing->value,
            self::Active->value,
            self::Grace->value,
        ];
    }
}
