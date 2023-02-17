<?php

namespace App\Repositories\Payments;

use App\Models\Transaction;
use App\Repositories\BaseRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

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
        dd('Processo de validação');
        #TODO::
        #Antes de finalizar a transferência, deve-se consultar um serviço autorizador externo,
        # use este mock para simular (https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6).

        #No recebimento de pagamento, o usuário ou lojista precisa
        # receber notificação (envio de email, sms) enviada por um
        # serviço de terceiro e eventualmente este serviço pode estar
        # indisponível/instável. Use este mock para simular o envio (http://o4d9z.mocklab.io/notify).

        return parent::beforeStore($attributes);
    }
}
