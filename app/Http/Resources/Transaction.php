<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Currency;

class Transaction extends JsonResource
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
            'id' => $this->id,
            'date' => $this->date,
            'customer' => [
                'id' => $this->customer_id,
                'name' => $this->customer->customerable_type == 'App\Models\IndividualCustomer' ? "{$this->customer->customerable->name}, {$this->customer->customerable->surname}" : $this->customer->customerable->name,
                'email' => $this->customer->customerable->email
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'type' => $this->type == 1 ? 'Buy' : 'Sell',
            'coins' => [
                'amount' => $this->type == 1 ? $this->to_amount : $this->from_amount,
                'symbol' => Currency::find($this->type == 1 ? $this->to_currency : $this->from_currency)->symbol
            ],
            'price' => $this->type == 1 ? $this->from_amount : $this->to_amount,
            'fees' => $this->fees,
            'payment_method' => $this->paymentMethod->method,
            // 'cost' => $this->when($this->type == 2, function(){
            //     // the cost method is 1, which estimated as amount * sell exchange date
            //     // todo: when added more than method , handle theme and return theme all
            //     $cost = $this->costs()->first();
            //     return [
            //         'amount' => $cost->cost,
            //         'exchange_rate' => $cost->data
            //     ];
            // })
        ];
    }
}
