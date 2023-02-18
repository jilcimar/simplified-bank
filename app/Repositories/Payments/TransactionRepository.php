<?php

namespace App\Repositories\Payments;

use App\Models\Transaction;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Transaction());
    }

    public function beforeStore($attributes): JsonResource|Transaction|JsonResponse
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

            DB::commit();
        } catch (\Exception) {
            DB::rollBack();
        }

        #TODO::
        #No recebimento de pagamento, o usuário ou lojista precisa
        # receber notificação (envio de email, sms) enviada por um
        # serviço de terceiro e eventualmente este serviço pode estar
        # indisponível/instável. Use este mock para simular o envio (http://o4d9z.mocklab.io/notify).

        return $resource;
    }
}
