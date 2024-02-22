<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Traits\UserAllocation;
use App\Validators\CustomValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    use UserAllocation;

    public function eventRegistration(Request $request){
        $data = $request->all();
        
        $rules = [
            'description' => ['required', 'string'],
            'date' => ['required'],
            'org_id' => ['required'],
            'class_room_id' => ['required'],
        ];

        $attributes = [
            'description' => 'description',
            'date' => 'date',
            'org_id' => 'organization',
            'class_room_id' => 'class room',
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

            
            $description = $request->input('description');
            $eventDate = date('Y-m-d', strtotime($request->input('date')));
            $orgId = $request->input('org_id');
            $classRoomId = $request->input('class_room_id');

            $event = Event::create([
                'description'=>$description,
                'event_date'=>$eventDate,
                'org_id'=>$orgId,
                'class_room_id'=>$classRoomId,
            ]);
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

    public function eventList(Request $request){
        $user = Auth::user();
        try {
            $classRoomInfo = $this->getUserRelatedClassRooms($user);
            if(!$classRoomInfo->isEmpty()){
            $events = Event::with('class_room', 'organization')
                ->whereIn('org_id', $classRoomInfo->pluck('org_id')->all())
                ->whereIn('class_room_id', $classRoomInfo->pluck('id')->all())
                ->orderBy('id','desc')->get();
            $events->transform(function($event){
                $organization = $event->organization ? [
                    'id' => $event->organization->id,
                    'name' => $event->organization->name,
                ] : (object)[];
                $class_room = $event->class_room ? [
                    'id' => $event->class_room->id,
                    'name' => $event->class_room->name,
                ] : (object)[];

                return [
                    'id'=>$event->getKey(),
                    'description'=>$event->description,
                    'event_date'=>$event->event_date,
                    'class_room'=>$class_room,
                    'organization'=>$organization,
                    'added_date'=>date('F d, Y', strtotime($event->created_at)),
                ];
            });
            return response()->json([
                'result'=>true,
                'eventList' => $events
            ],200);
        } else {
            return response()->json([
                'result' => false,
                'errors' => ['Dont have allocated organizations']
            ], 400);
        }

        } catch(Exception $e){
            return response()->json([
                'result' => false,
                'errors' => 'Database connection error: ' . $e->getMessage()
            ], 500);
        }

    }

    public function eventUpdate(Request $request){
        $data = $request->all();
        
        $rules = [
            'description' => ['required', 'string'],
            'date' => ['required'],
            'org_id' => ['required'],
            'class_room_id' => ['required'],
        ];

        $attributes = [
            'description' => 'description',
            'date' => 'date',
            'org_id' => 'organization',
            'class_room_id' => 'class room',
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

            $event = Event::findOrFail($request->input('id'));

            $event->description = $request->input('description');
            $event->event_date = date('Y-m-d', strtotime($request->input('date')));
            $event->org_id = $request->input('org_id');
            $event->class_room_id = $request->input('class_room_id');
            $event->save();

            DB::commit();

            return response()->json([
                'result' => true,
                'message' => 'Record has been successfuly updated'
            ], 200);
            
        } catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'result'=>false,
                'errors' => $e->getMessage()
            ],500);
        }
    }

    public function eventDestroy(Request $request){
        try {
            DB::beginTransaction();
    
            $event = Event::findOrFail($request['eventId']);
            $event->delete();
    
            DB::commit();
            return response()->json([
                'result' => true,
                'message' => 'Record has been successfuly removed'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'result'=>false,
                'errors' => $e->getMessage()
            ],500);
        }
    }
}
