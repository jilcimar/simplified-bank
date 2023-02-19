<?php

namespace App\Repositories\Payments;

use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\WalletExtract;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Transaction());
    }

    public function beforeStore($attributes): JsonResource|Transaction
    {
        $attributes['payer_id'] = $attributes['payer'];
        $attributes['payee_id'] = $attributes['payee'];


        return parent::beforeStore($attributes);
    }

    public function afterSave(Model $resource, Collection|array $attributes): Model|JsonResource
    {
        DB::beginTransaction();

        try {
            $payer = $resource->payer;
            $payee = $resource->payee;

            $payer->wallet->amount -= $resource->value;
            $payer->wallet->save();

            $payee->wallet->amount += $resource->value;
            $payee->wallet->save();

            WalletExtract::create([
                'wallet_id' => $payer->wallet->id,
                'amount' => $payer->wallet->amount,
            ]);

            WalletExtract::create([
                'wallet_id' => $payee->wallet->id,
                'amount' => $payee->wallet->amount,
            ]);

            DB::commit();
        } catch (\Exception) {
            DB::rollBack();
        }

        return new TransactionResource($resource);
    }
}
