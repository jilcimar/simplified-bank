<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'payer' => $this->payer->email,
            'payee' => $this->payee->email,
            'value' => $this->value,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
        ];
    }
}
