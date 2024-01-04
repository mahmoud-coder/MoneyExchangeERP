<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;
use App\Models\Phone;
use App\Models\Comment;

class Customer extends Model
{
    use HasFactory;
    
    public function customerable(){
        return $this->morphTo();
    }

    public function files(){
        return $this->morphMany(File::class, 'fileable');
    }

    public function phones(){
        return $this->morphMany(Phone::class, 'phoneable');
    }

    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function delete(){
        $this->customerable->delete();
        parent::delete();
    }
}
