<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function account()
    {
        return $this->hasOne(COA::class, 'item_id');
    }

    public function delete()
    {
        $this->account()->delete();
        return parent::delete();
    }
}
