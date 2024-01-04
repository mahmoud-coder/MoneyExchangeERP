<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntityCustomer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function customer(){
        return $this->morphOne(Customer::class, 'customerable');
    }
    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function activity(){
        return $this->belongsTo(Activity::class);
    }
    public function share_holders(){
        return $this->hasMany(ShareHolder::class);
    }
}
