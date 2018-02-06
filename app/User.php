<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use Uuids;
    public $incrementing = false;
    protected $casts = [
    'id' => 'string'
];

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

    public function sendItems()
    {
      return $this->hasMany('App\Message','user_from','id');
    }
    public function inbox()
    {
      return $this->hasMany('App\Message','user_to','id');
    }

    public function allMessages()
    {
      return $this->sendItems->merge($this->inbox);
    }

    public function unreadMsg()
    {
      return $this->inbox()->where('status',0);
    }
    public function readedMsg()
    {
      return $this->inbox()->where('status',1);
    }
}
