<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class);
    }

    public function journal_entry()
    {
        return $this->morphOne(GeneralJournal::class , 'itemable');
    }

    public function delete(){
        $this->journal_entry()->delete();
        return parent::delete();
    }

    public function getTypeAsStringAttribute()
    {
        return $this->attributes['type'] == 1 ? 'purchase' : 'sale';
    }
}
