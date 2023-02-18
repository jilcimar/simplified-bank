<?php

namespace App\Models\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasOnePayer
{
    /**
     * Get the Offer Freight.
     */
    public function payer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'payer_id');
    }
}
