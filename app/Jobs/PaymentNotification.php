<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class PaymentNotification implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $response = Http::get('http://o4d9z.mocklab.io/notify');
            $data = $response->json();

            if (isset($data['message']) && $data['message'] == 'Success') {
                //TODO:: Envio feito com sucesso!
            }
        } catch (\Exception $exception) {
            //TODO:: Disparar um log para monitoramento de falhas.
        }
    }
}
