<?php

namespace App\Http\Controllers\API\V1;

use App\Events\ChatEvent;
use App\Events\GroupChatEvent;
use App\Events\NewChatMessage;
use App\Http\Controllers\Controller;
use App\Jobs\StartChatNotification;
use App\Models\Chat;
use App\Models\ChatGroup;
use App\Models\ChatGroupUser;
use App\Models\User;
use App\Traits\UserAllocation;
use App\Validators\CustomValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function chatGroupList(Request $request){
        $user = Auth::user();
        try {
            $userChatGroups = ChatGroupUser::where('user_id', $user->getKey())->pluck('group_id');
            if(!$userChatGroups->isEmpty()){
                $chatGroups = ChatGroup::whereIn('id', $userChatGroups)->get();
                $chatGroups->transform(function($grp){
                    $unseenMessages = $grp->groupUnSeenMessages($grp->id);
                    return [
                        'id'=>$grp->id,
                        'name'=>$grp->name,
                        'unseen_messages'=>$unseenMessages->count()
                    ];
                });
            } else {
                $chatGroups = collect([]);
            }
            
            return response()->json([
                'result'=>true,
                'chatGroupList' => $chatGroups
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

        if($request->chatType == 0){
            $message->receiver_id = $request->userId;
            $message->group_id = NULL;
        } else {
            $message->receiver_id = NULL;
            $message->group_id = $request->userId;
        }
        $message->message = $request->message;
        $message->save();

        if($request->chatType == 0){
            $updateMessage = Chat::with(['sender', 'receiver'])->find($message->id);
            event(new NewChatMessage($updateMessage));

            $params = [
                'sender_id'=>$user->getKey(),
                'receiver_id'=>$request->userId,
                'group_id'=>NULL
            ];
            $data = [
                'sender'=>$updateMessage->sender->name,
                'message'=>$updateMessage->message,
                'receiver'=> [
                    [
                    'name' => $updateMessage->receiver->name,
                    'email' => $updateMessage->receiver->email
                    ]
                ],
            ];
        } else {
            $updateMessage = Chat::with(['sender', 'group'])->find($message->id);
            event(new GroupChatEvent($updateMessage));

            $params = [
                'sender_id'=>$user->getKey(),
                'receiver_id'=>NULL,
                'group_id'=>$request->userId
            ];

            $group = ChatGroup::findOrFail($request->userId);
            $groupUsers = $group->users->whereNotIn('id', [$user->id]);
            $receivers = [];
            foreach ($groupUsers as $receiver) {
                $receivers[] = [
                    'name' => $receiver->name,
                    'email' => $receiver->email
                ];
            }
            $data = [
                'sender'=>$updateMessage->sender->name,
                'message'=>$updateMessage->message,
                'receiver'=>$receivers
            ];
        }

        $chatCount = Chat::where($params)->count();
        if($chatCount == 1){
            dispatch(new StartChatNotification($data));
        }

        return response()->json([
            'result'=>true,
            'message' => $updateMessage,
            'data'=>$data,
            'chatCount'=>$chatCount
        ],200);
    }

    public function updateMessageSeen(Request $request){
        $user = Auth::user();
        $message = Chat::where('receiver_id', $user->getKey())->where('sender_id', $request->userId)->where('seen', 1)->whereNull('group_id')->get();
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

    public function userGroupMessages(Request $request){
        $user = Auth::user();
        $messageQuery = Chat::getMessagesQueryBetweenUserAndGroup($request, $user->getKey(), $request->groupId);
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
            'groupMessageList' => $messages
        ],200);
    }

    public function chatRoomRegistration(Request $request){
        $user = Auth::user();
        $data = $request->all();
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'users' => ['required'],
        ];
        $attributes = [
            'name' => 'chat room name',
            'users' => 'users'
        ];
        $validator = CustomValidator::validate($data, $rules, $attributes);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $formattedErrors = [];

            foreach ($errors as $field => $messages) {
                $formattedErrors[$field] = $messages[0];
            }

            return response()->json([
                'result' => false,
                "errors" => $formattedErrors,
            ], 403);
        }

        try {
            DB::beginTransaction();

            $classRoom = ChatGroup::create([
                'name'=>$request['name'],
                'admin_id'=>$user->getKey(),
            ]);

            if(isset($request['users']) && sizeof($request['users'])> 0){
                $users = $request->input('users', []);
                $users[] = $user->getKey();
                $classRoom->users()->sync($users);
            }
            DB::commit();

            return response()->json([
                'result' => true,
                'message' => 'Record has been successfuly added'
            ], 200);
        } catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'result'=>false,
                'errors' => $e->getMessage()
            ],500);
        }
    }

    public function updateGroupMessageSeen(Request $request){
        $user = Auth::user();
        $message = Chat::where('sender_id', '!=' ,$user->getKey())->where('group_id', $request->groupId)->where('seen', 1)->whereNull('receiver_id')->get();
        if($message->count()>0){
            foreach($message AS $msg){
                $msg->update(['seen'=>0]);
            }
        }
        
        return response()->json([
            'result'=>true,
            'message' => $message,
            'group'=> $request->groupId
        ],200);
    }
}
