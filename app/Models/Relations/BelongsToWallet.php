<?php

namespace App\Models\Relations;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToWallet
{
    /**
     * Get the Offer Freight.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
