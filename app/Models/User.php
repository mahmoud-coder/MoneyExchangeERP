<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarSrcAttribute(){
        return $this->attributes['avatar_src']
                ? '/storage/avatars/' . $this->attributes['avatar_src']
                : '/assets/img/avatar.svg' ;
    }

    public function getRoleTextAttribute(){
        switch($this->attributes['role']){
            case 0:
                return 'CEO / Admin';
                break;
            case 1:
                return 'Sales Team';
                break;
            case 2:
                return 'Compliance team';
                break;
            case 3:
                return 'Accounting Team';
                break;
            default:
                throw new \Exception('un-knowen role number!');
        }
    }
}
