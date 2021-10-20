<?php

namespace App\Http\Controllers;

use App\Models\UserDevice;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends ApiController
{   
    public function addDeviceKey(Request $request){

        $validator = Validator::make($request->all(), [
            'device_key' => 'required'
        ]);

        if($validator->fails()){
            return $this->respondWithError(
                'Validation Error',
                $validator->errors(),
                422
            );
        }

        try {
            $device = UserDevice::where('device_key', $request->device_key);
    
            if($device->exists()){
                return response()->json([
                    'new_device' => false,
                    'status' => 200,
                    'message' => "You have already added the device key",
                ], 200);
            }
    
            UserDevice::create(['device_key' => $request->device_key]);

            return response()->json([
                'new_device' => true,
                'status' => 200,
                'message' => "You have successfully added the device key",
            ], 200);

        } catch (Exception $e) {

            Log::info($e->getMessage());

            return $this->respondWithError(
                'Something is wrong. Try again.',
                $e->getMessage(),
                500
            );
        }
    }


    public function sendNotification(Request $request)
    {   
        // $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $firebaseToken = ["eGXRMA4cToumGxvgORw1Wx:APA91bGGlozK6ieD_dxX1OaUjntgUGDiP_zMCrn_O9sVu1isif737gZjCzSUwDXBQ_Rh1hL-WtY8DJBp7ztP-vv6pxv8u1VvewKl-kNDuJS-NDsBo4JZe6u7vUVBHgpdeGaCNKa6mO29"];
        try{

            
            $SERVER_API_KEY = 'AAAALvWF3e0:APA91bED2oAA6Gl4dKTA-DYwh8JArmJLjLNxXDyRW3aDLkjymy89wB1_Vjay5uC66Cx7UP2jOSjtHQyUbQm9pu31sVl2Da1Zx7l-fJxzoAWUn2t4vAfEhgAu40U9YX8QkFNlmHWxUFQY';

            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => "Hello Server title",
                    "body" => "Hello Server body",
                    "content_available" => true,
                    "priority" => "high",
                ]
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);

            dd($response);

        } catch(Exception $e){
            dd($e);
        }
        //  https://console.firebase.google.com/project/(your-project-id)/settings/cloudmessaging 
        //  https://console.firebase.google.com/project/testing-msg-475f8/settings/cloudmessaging 
    
        // http://127.0.0.1:8000/send-notification
    
    }
}
