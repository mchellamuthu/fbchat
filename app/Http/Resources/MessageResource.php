<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MessageResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id'=>$this->id,
          'user_from'=>$this->user_from,
          'user_to'=>$this->user_to,
          'sender_name'=>$this->sender->name,
          'msg'=>(string)$this->msg,
          'status'=>$this->status,
          'created_at'=>(string)$this->created_at,
        ];
    }
}
