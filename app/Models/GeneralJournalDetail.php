<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralJournalDetail extends Model
{
    use HasFactory;

    protected $guarded = []; // all attributes are mass assignable
    
    public function general_journal(){
        return $this->belongsTo(GeneralJournal::class);
    }
    public function account(){
        return $this->belongsTo(COA::class);
    }
    public function setTypeAttribute($v){
        $v = strtolower($v);
        if( $v == 'debit' ||  $v == 'credit'){
            $this->attributes['type'] = $v == 'debit' ? 0 : 1;
        }else{
            throw new \Exception('The General Journal Detail type should be `Debit` or `Credit`');
        }
        
    }
    public function getTypeAttribute()
    {
        return $this->attributes['type'] == 0 ? 'Debit' : 'Credit';
    }

    public function getAmountAttribute()
    {
        return (float) $this->attributes['amount'];
    }
}
