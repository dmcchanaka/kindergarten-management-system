<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityImage;
use App\Traits\UserAllocation;
use App\Validators\CustomValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class GalleryController extends Controller
{
    use UserAllocation;
    
    public function galleryRegistration(Request $request){
        $data = $request->all();

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'feature_image' => ['required'],
            'org_id' => ['required'],
            'class_room_id' => ['required'],
        ];

        $attributes = [
            'title' => 'title',
            'description' => 'description',
            'feature_image' => 'feature image',
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

            $title = $request->input('title');
            $description = $request->input('description');
            $orgId = $request->input('org_id');
            $classRoomId = $request->input('class_room_id');

            $featureImageUrl = "";
            $featureImage = $request->file('feature_image');
            if ($featureImage) {
                $imageName = md5(uniqid() . microtime()) . '.'.$featureImage->getClientOriginalExtension();
                $path = storage_path('app/public/classroom/images/' . $imageName);
            
                // Resize and save the image
                Image::make($featureImage)
                        ->resize(500, 500, function ($constraint) {
                            $constraint->aspectRatio();
                            // $constraint->upsize();
                        })
                    ->save($path);
                $featureImageUrl = Storage::url('public/classroom/images/'.$imageName);
            }

            $activity = Activity::create([
                'title'=>$title,
                'description'=>$description,
                'feature_img_url'=>$featureImageUrl,
                'org_id'=>$orgId,
                'class_room_id'=>$classRoomId,
            ]);


            $contentImages = $request->file('content_images');
            if ($contentImages) {
                foreach ($contentImages as $contentImage) {
                    $imageName = md5(uniqid() . microtime()) . '.'.$contentImage->getClientOriginalExtension();
                    $path = storage_path('app/public/classroom/images/' . $imageName);
            
                    // Resize and save the image
                    Image::make($contentImage)
                        ->resize(500, 500, function ($constraint) {
                            $constraint->aspectRatio();
                            // $constraint->upsize();
                        })
                        ->save($path);
                    $subImageUrl = Storage::url('public/classroom/images/'.$imageName);
            
                    $activityImages = ActivityImage::create([
                        'activity_id'=>$activity->getKey(),
                        'activity_img_url'=>$subImageUrl,
                    ]);
                }
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

    public function fetchContentList(Request $request){
        $user = Auth::user();
        try {
            $classRoomInfo = $this->getUserRelatedClassRooms($user);
            if(!$classRoomInfo->isEmpty()){
                $activities = Activity::with('activity_images', 'class_room', 'organization')->whereIn('org_id', $classRoomInfo->pluck('org_id')->all())->whereIn('class_room_id', $classRoomInfo->pluck('id')->all())->orderBy('id','desc')->get();
                $activities->transform(function($act){
                    $activityImages = $act->activity_images->map(function($image){
                        return [
                            'image_url' => url('/') . $image->activity_img_url
                        ];
                    });
                    $organization = $act->organization ? [
                        'id' => $act->organization->id,
                        'name' => $act->organization->name,
                    ] : (object)[];
                    $class_room = $act->class_room ? [
                        'id' => $act->class_room->id,
                        'name' => $act->class_room->name,
                    ] : (object)[];
                    return [
                        'id'=>$act->getKey(),
                        'title'=>$act->title,
                        'description'=>$act->description,
                        'feature_image'=>url('/') .$act->feature_img_url,
                        'content_images'=>$activityImages,
                        'class_room'=>$class_room,
                        'organization'=>$organization,
                        'added_date'=>date('F d, Y', strtotime($act->created_at)),
                    ];
                });
                return response()->json([
                    'result'=>true,
                    'activitiesList' => $activities
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

    public function galleryUpdate(Request $request){
        $data = $request->all();

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'feature_image' => ['required'],
            'org_id' => ['required'],
            'class_room_id' => ['required'],
        ];

        $attributes = [
            'title' => 'title',
            'description' => 'description',
            'feature_image' => 'feature image',
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

            $activity = Activity::findOrFail($request->input('id'));

            // Delete existing feature image
            if ($activity->feature_img_url) {
                $existingFeatureImagePath = public_path($activity->feature_img_url);
                if (file_exists($existingFeatureImagePath)) {
                    unlink($existingFeatureImagePath);
                }
            }

            // Delete existing images
            $existingActivityImgs = $activity->activity_images;
            foreach ($existingActivityImgs as $existingImage) {
                // Delete image file from storage
                $imagePath = public_path($existingImage->activity_img_url);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                // Delete record from the database
                $existingImage->delete();
            }

            $activity->title = $request->input('title');
            $activity->description = $request->input('description');
            $activity->org_id = $request->input('org_id');
            $activity->class_room_id = $request->input('class_room_id');

            

            $featureImageUrl = "";
            $featureImage = $request->file('feature_image');
            if ($featureImage) {
                $imageName = md5(uniqid() . microtime()) . '.'.$featureImage->getClientOriginalExtension();
                $path = storage_path('app/public/classroom/images/' . $imageName);
            
                // Resize and save the image
                Image::make($featureImage)
                        ->resize(500, 500, function ($constraint) {
                            $constraint->aspectRatio();
                            // $constraint->upsize();
                        })
                    ->save($path);
                $featureImageUrl = Storage::url('public/classroom/images/'.$imageName);
                $activity->feature_img_url = $featureImageUrl;
            }
            $activity->save();

            //insert new activity images
            $contentImages = $request->file('content_images');
            if ($contentImages) {
                foreach ($contentImages as $contentImage) {
                    $subImageName = md5(uniqid() . microtime()) . '.' . $contentImage->getClientOriginalExtension();
                    $path = storage_path('app/public/classroom/images/' . $subImageName);
            
                    // Resize and save the image
                    Image::make($contentImage)
                        ->resize(500, 500, function ($constraint) {
                            $constraint->aspectRatio();
                            // $constraint->upsize();
                        })
                        ->save($path);
                    $subImageUrl = Storage::url('public/classroom/images/'.$subImageName);
            
                    $activityImage = new ActivityImage();
                    $activityImage->activity_id = $activity->getKey();
                    $activityImage->activity_img_url = $subImageUrl;
                    $activityImage->save();
                }
            }

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

    public function galleryRemove(Request $request){
        try {
            DB::beginTransaction();
    
            $gallery = Activity::findOrFail($request['contentId']);
            $gallery->delete();
    
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
