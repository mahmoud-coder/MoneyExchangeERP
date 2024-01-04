<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class Customer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $type = $this->customerable_type == 'App\\Models\\IndividualCustomer' ? 'Individual' : 'Entity';
        if($this->creator_type == 'sumsub'){
            $creator = 'Sumsub.com';
        }elseif($this->creator_type == 'self'){
            $creator = "his/him self";
        }elseif($this->creator_type == 'Uploaded Sheet'){
             $creator = "Uploaded using Excel Sheet";
        }else{ // user
            $creator = User::find($this->creator_id)->name;
        }
        
        return [
            'id' => $this->id,
            'type' => $type,
            'name' => ($type == 'Entity') ? $this->customerable->name : " {$this->customerable->name}, {$this->customerable->surname}",
            'email' => $this->customerable->email,
            'creator' => $creator
        ];
    }
}
