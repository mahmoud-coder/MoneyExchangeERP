<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralJournal extends Model
{
    use HasFactory;

    protected $guarded = []; // all attributes are mass assignable

    public function details(){
        return $this->hasMany(GeneralJournalDetail::class);
    }

    public function itemable()
    {
        return $this->morphTo();
    }
}
