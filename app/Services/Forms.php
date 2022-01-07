<?php

declare(strict_types=1);

namespace App\Services;

use App\Facades\Network;
use Illuminate\Support\Str;

final class Forms
{
    private static array $transactionOptionsValues = [
        'all',
        'transfer',
        'secondSignature',
        'delegateRegistration',
        'delegateResignation',
        'vote',
        'voteCombination',
        'multiSignature',
        'ipfs',
        'multiPayment',
        'timelock',
        'timelockClaim',
        'timelockRefund',
        'magistrate',
    ];

    /**
     * Map the transaction type options as the rich select component expects.
     *
     * return array
     */
    public static function getTransactionOptions(): array
    {
        $options = collect(static::$transactionOptionsValues)
            ->mapWithKeys(fn ($option) => [$option =>__('forms.search.transaction_types.'.$option)]);

        if (! Network::hasTimelock()) {
            $options = $options->filter(fn ($value, $key) => Str::contains($key, 'timelock') === false);
        }

        return $options->toArray();
    }
}
