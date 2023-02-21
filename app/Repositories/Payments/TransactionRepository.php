<?php

namespace App\Repositories\Payments;

use App\Http\Resources\TransactionResource;
use App\Jobs\PaymentNotification;
use App\Models\Transaction;
use App\Models\WalletExtract;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $attributes['uuid'] = Str::uuid()->toString() . now()->format('Y-m-d-H-m-s-i');

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


            $this->saveExtract($payer->wallet->id, $payer->wallet->amount);
            $this->saveExtract($payee->wallet->id, $payee->wallet->amount);

            PaymentNotification::dispatch();

            DB::commit();
        } catch (\Exception) {
            DB::rollBack();
        }

        return new TransactionResource($resource);
    }

    public function saveExtract(int $walletId, int $amount): void
    {
        WalletExtract::create([
            'wallet_id' => $walletId,
            'amount' => $amount,
        ]);
    }
}
