<?php

namespace App\Helper;

use App\Type\TransactionType;

class TransactionTypeHelper
{
    public static function stringToTransactionRole(string $transactionRole): TransactionType
    {
        return match ($transactionRole) {
            TransactionType::IN->value => TransactionType::IN,
            TransactionType::OUT->value => TransactionType::OUT,
            TransactionType::ADJUSTMENT->value => TransactionType::ADJUSTMENT,
            TransactionType::EXPIRED->value => TransactionType::EXPIRED,
            'default' => throw new \Exception('Transaction Type is not valid')
        };
    }

    public static function transactionTypeToString(TransactionType $transactionType): string
    {
        return $transactionType->value;
    }

    public static function getArrayOptions(): array
    {
        return [TransactionType::IN, TransactionType::OUT, TransactionType::ADJUSTMENT, TransactionType::EXPIRED];
    }
}
