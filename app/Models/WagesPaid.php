<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WagesPaid extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function journal_entry()
    {
        return $this->morphOne(GeneralJournal::class, 'itemable');
    }

    public function payout()
    {
        return $this->belongsTo(WagesPayout::class, 'wages_payout_id');
    }

    public function delete()
    {
        $this->journal_entry()->delete();
        return parent::delete();
    }
}
