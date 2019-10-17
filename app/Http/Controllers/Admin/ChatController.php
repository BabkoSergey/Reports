<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

use App\Events\MessageSent;
use App\Message;

class ChatController extends Controller
{
    private $prepage;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:admin panel'); 
        
        $this->prepage = 10;
    }
    
    public function fetchMessages(Request $request)
    {
        $offset = intval($request->get('offset')) ?? 0;
                
        $collection = Message::with('user')->orderBy('id', 'desc')->get()->splice($offset)->take($this->prepage)
                                ->each(function ($message) {                                          
                                    $message->user->avatar = $message->user->getUserAvatar();
                                    $message->user->fullName = $message->user->getFullName();
                                    $message->user->labelName = $message->user->getFullNameAbr();
                                    $message->user->shortName = $message->user->getShortFullName();
                                });
        
        $messages = $collection->reverse()->values()->all();
        
        return $messages;
    }

    public function sendMessage(Request $request)
    {
        $message = auth()->user()->messages()->create([
            'message' => $request->message
        ]);

        broadcast(new MessageSent(auth()->user(), $message));

        return ['status' => 'Message Sent!'];
    }
    
}
