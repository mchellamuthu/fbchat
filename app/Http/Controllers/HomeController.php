<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
// use App\Conversation;
use App\Message;
use Auth;
use App\Events\SendMessage;
use Validator;
use App\Http\Resources\MessageResource;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('messages.index');
    }
    public function users()
    {
        $user = Auth::user();
        $inbox = $user->inbox()->groupBy('user_from')->orderBy('created_at','desc')->get();
        $send = $user->sendItems()->groupBy('user_to');
        // $messages = $inbox->union($send)->orderBy('created_at','desc')->get();
        $users = $inbox->map(function($item){
            $user = Auth::user();
            $id = $item->sender->id;
            $name = $item->sender->name;
          $auser = User::where('id',$id)->first();
          $msg = $auser->inbox()->where('user_from',$user->id)->orderBy('created_at','desc')->get();
          if ($msg->count() > 0) {
            $messages = $msg ;
            $type="Send";
          }else{
            $messages = $auser->sendItems()->where('user_to',$user->id)->orderBy('created_at','desc')->get();
            $type="Receive";
          }
          return [
            'id'=>$id,
            'name'=>$name,
            'type'=>$type,
            'msg'=>$messages,
            'lastmsg'=>'',

          ];
        })->unique()->values()->all();
        return response()->json(['data'=>$users]);
    }

    public function getMessages($id)
    {
      $chats = Message::where(function($query) use($id){
        $query->where('user_from', '=', Auth::user()->id)
         ->where('user_to', '=', $id);
       })->orWhere(function ($query) use ($id) {
         $query->where('user_from', '=', $id)
         ->where('user_to', '=', Auth::user()->id);
      })->orderBy('id','asc')->get();

      return response()->json(['data'=>$chats]);
    }

    public function SendMessage($id,Request $request)
    {
      $validation = Validator::make($request->all(),[
        'message'=> 'required',
      ]);
      if ($validation->fails()) {
        return response()->json([
          'errors'=>$validation->messages()
        ]);
      }
      $user = Auth::user();
      $chat = Message::create([
        'user_from'=>$user->id,
        'user_to'=>$id,
        'msg'=>$request->message,
        'status'=>false
      ]);

      // $message = new MessageResource(
      $message = [
        'id'=>$chat->id,
        'user_from'=>$chat->user_from,
        'user_to'=>$chat->user_to,
        'sender_name'=>$chat->sender->name,
        'msg'=>(string)$chat->msg,
        'status'=>$chat->status,
        'created_at'=>(string)$chat->created_at,
      ];

      event(new SendMessage($message));
      return response()->json(['data'=>$chat]);
    }

     public function markAsRead($id)
    {
      $user = Auth::user();
      $msg = Message::where('id',$id)->first();
      $msg->update([
        'status'=>true,
      ]);
      return response()->json(['data'=>$msg]);
    }
}
