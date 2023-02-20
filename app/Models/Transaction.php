<?php

namespace App\Models;

use App\Models\Relations\HasOnePayee;
use App\Models\Relations\HasOnePayer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    use HasOnePayer;
    use HasOnePayee;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'value',
        'payer_id',
        'payee_id',
        'uuid'
    ];
}
