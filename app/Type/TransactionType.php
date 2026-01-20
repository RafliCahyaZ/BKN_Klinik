<?php

namespace App\Type;

enum TransactionType: string
{
    case IN = 'in';
    case OUT = 'out';
    case ADJUSTMENT = 'adjustment';
    case RETURN = 'return';
    case EXPIRED = 'expired';
}
