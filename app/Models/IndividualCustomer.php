<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;

class IndividualCustomer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function customer(){
        return $this->morphOne(Customer::class, 'customerable');
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }
}
