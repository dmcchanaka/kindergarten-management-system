<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Validators\CustomValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
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
}
