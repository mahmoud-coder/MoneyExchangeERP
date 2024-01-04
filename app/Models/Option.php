<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public static function get($name,$defalut = null){
        try{
            return static::firstWhere('name',$name)->value ?? $defalut ;
        }
        catch(\Exception $e){
            return $defalut;
        }
    }
    
    public static function set($name,$value){
        static::updateOrCreate(
            ['name' => $name ],
            ['value' => $value ?? '']
        );
    }
}
