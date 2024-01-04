<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WagesPayout extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['paid_status'];
    protected $with = ['details.employee:id,name,surname'];

    public function journal_entry()
    {
        return $this->morphOne(GeneralJournal::class,'itemable');
    }


    public function details()
    {
        return $this->hasMany(WagesPayoutsDetail::class);
    }

    public function paids()
    {
        return $this->hasMany(WagesPaid::class);
    }

    public function delete(){
        $this->journal_entry()->delete();
        $this->paids->each( fn($p) => $p->journal_entry()->delete() );
        return parent::delete();
    }

    /**
     * @return string "not paid" | "some paid" | "all paid"
     */
    public function getPaidStatusAttribute()
    {
        $not_paid_count = $this->details()->whereNull('wages_paid_id')->count();
        $paid_count = $this->details()->whereNotNull('wages_paid_id')->count();

        if($not_paid_count == 0 && $paid_count > 0) return "all paid";
        if($not_paid_count >= 0 && $paid_count == 0) return "not paid";
        if($not_paid_count > 0 && $paid_count > 0) return "some paid";
    }
}
