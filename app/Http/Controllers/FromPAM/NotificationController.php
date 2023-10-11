<?php

namespace App\Http\Controllers\FromPAM;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PamNotification;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function new_notification(Request $request){
        $rules=array(
            'app_code' => 'required',
            'patient_id' => 'required',
            'title' => 'required|max:254',
            'description' => 'required',
            'type' => 'required'
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'status' => 'validation error',
                'message' => $validator->errors()
            ],419);
//            return ;
        }

        if ($request->app_code != env('FROM_PAM_CODE')){
            return response()->json([
               'status' => false,
               'message' => 'Неверный код'
            ],201);
        }
        $get_user = User::where('client_id', $request->patient_id)->first();
        if ($get_user == null){
            return response()->json([
               'status' => false,
               'message' => 'Неверный patient_id'
            ],201);
        }


        PamNotification::create([
            'user_id' => $get_user->id,
            'client_id' => $request->patient_id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'order_id' => $request->order_id,
            'service_id' => $request->service_id,
            'status_from_order' => $request->status_from_order
        ]);

        return response()->json([
           'status' => true,
           'message' => 'Notification Created'
        ],200);

    }
}
