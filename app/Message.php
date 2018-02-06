<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
  use Uuids;
    protected $guarded = [];
    public $incrementing =false;

    public function sender()
    {
      return $this->belongsTo('App\User','user_from','id');
    }
    public function receiver()
    {
      return $this->belongsTo('App\User','user_to','id');
    }
}
