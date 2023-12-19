<?php

namespace App\Http\Controllers\API\V1;

use App\Events\ChatEvent;
use App\Events\NewChatMessage;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use App\Traits\UserAllocation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    use UserAllocation;

    public function chatUserList(Request $request){
        try {
            $user = Auth::user();
            $users = $this->getChatUserList($user);
            $users->transform(function($user){
                return [
                    'id'=>$user->id,
                    'first_name'=>$user->first_name,
                    'last_name'=>$user->last_name,
                    'user_role'=>$user->userRole(),
                    'unseen_messages'=>$user->unSeenMessages->count()
                ];
            });
            return response()->json([
                'result'=>true,
                'userList' => $users
            ],200);

        } catch(Exception $e){
            return response()->json([
                'result' => false,
                'errors' => 'Database connection error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function userMessages(Request $request){
        $user = Auth::user();
        $messageQuery = Chat::getMessagesQueryBetweenTwoUsers($request, $user->getKey(), $request->userId);
        if(isset($request->earlierDate)){
            $formattedDate = (new \DateTime($request->earlierDate))->format("Y-m-d H:i:s");
            $messageQuery->where('created_at', '<', $formattedDate);
        }
        $messages = $messageQuery->orderBy('created_at', 'DESC')->limit($request->limit ?? 10)->get();

        if($messages->count()){
            foreach($messages AS $msg){
                if($msg->receiver_id === $user->getKey()){
                    $msg->update(['seen'=>0]);
                }
            }
        }
        // Sort messages collection by "id" in ascending order
        $messages = $messages->sortBy('id')->values();

        return response()->json([
            'result'=>true,
            'messageList' => $messages
        ],200);
    }

    public function userOldMessages(Request $request){
        $user = Auth::user();
        $messageQuery = Chat::getMessagesQueryBetweenTwoUsers($request, $user->getKey(), $request->userId);
        if(isset($request->earlierDate)){
            $formattedDate = (new \DateTime($request->earlierDate))->format("Y-m-d H:i:s");
            $messageQuery->where('created_at', '<', $formattedDate);
        }
        $messages = $messageQuery->orderBy('created_at', 'DESC')->limit($request->limit ?? 10)->get();

        if($messages->count()){
            foreach($messages AS $msg){
                if($msg->receiver_id === $user->getKey()){
                    $msg->update(['seen'=>0]);
                }
            }
        }
        // Sort messages collection by "id" in ascending order
        $messages = $messages->sortBy('id')->values();

        return response()->json([
            'result'=>true,
            'messageList' => $messages
        ],200);
    }

    public function sendMessage(Request $request){
        $user = Auth::user();

        $message = new Chat();
        $message->sender_id = $user->getKey();
        $message->receiver_id = $request->userId;
        $message->message = $request->message;
        $message->save();

        $updateMessage = Chat::with(['sender', 'receiver'])->find($message->id);

        event(new NewChatMessage($updateMessage));
        // broadcast(new NewChatMessage($request->message, $user->name))->toOthers();
        return response()->json([
            'result'=>true,
            'message' => $updateMessage,
        ],200);
    }

    public function updateMessageSeen(Request $request){
        $user = Auth::user();
        $message = Chat::where('receiver_id', $user->getKey())->where('sender_id', $request->userId)->where('seen', 1)->get();
        if($message->count()>0){
            foreach($message AS $msg){
                $msg->update(['seen'=>0]);
            }
        }
        
        return response()->json([
            'result'=>true,
            'message' => $message,
            'sender'=> $request->userId
        ],200);
    }
}
