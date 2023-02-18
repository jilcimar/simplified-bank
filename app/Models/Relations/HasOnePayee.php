<?php

namespace App\Models\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasOnePayee
{
    /**
     * Get the Offer Freight.
     */
    public function payee(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'payee_id');
    }
}
