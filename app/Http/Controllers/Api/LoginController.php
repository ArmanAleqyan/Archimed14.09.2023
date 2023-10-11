<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Feedback;
use App\Models\LoginCount;
use App\Models\SendCallValidation;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Validator;
use App\Models\RegisterFeedbackChat;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/LoginForPhone",
     * summary="LoginForPhone",
     * description=" Отправлает  код потвердения  на номер телефона   отправить  код  повторна  работает та же функцыя",
     * operationId="Login For Phone",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="phone", type="string", format="text", example="+37493073584"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description=" User Login  For Phone",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */


    public function LoginForPhone(Request $request){
        $rules=array(
            'phone' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }

        $getCall =   SendCallValidation::where('phone', $request->phone)->where('type', 'login')->where('created_at', '>', Carbon::now()->subMinutes(10))->get();


        if($getCall->count() > 2){
            $getCall->last();
            $time =$getCall->last()->created_at->addminutes(10)->diff(Carbon::now())->format('%i minutes');
            return response()->json([
                'status' => false,
                'minutes' =>$time ,
                'message' => 'try again in 10 minutes'
            ], 422);
        }else{

            SendCallValidation::create([
                'phone' =>  $request->phone,
                'type' => 'login'
            ]);

        }

        $get_user = User::where('phone', $request->phone)->where('phone_veryfi_code',1)->first();
        if($get_user == null){
            return response()->json([
                'status'=>false,
                'data' => [
                    'message' =>  'Wrong User Phone',
                ],
            ],422);
        }
        $data = $request->phone;
        $rand = mt_rand(100000,999999);

        $client = new Client();

        $login = env('sms_login');
        $password_sms = env('sms_password');
        $response = $client->request('GET', 'https://smsc.ru/sys/send.php', [
            'query' => [
                'login' => $login,
                'psw' => $password_sms,
                'phones' => $request->phone,
                'sender' => 'Arman',
                'mes' => "Ваш код подтверждения   $rand",
            ],
        ]);

        User::where('phone', $request->phone)->update([
            'phone_code' => $rand
        ]);
        return response()->json([
            'status'=>true,
            'data' => [
                'user_id' => $get_user->id,
                'message' =>  'Code Send Your Phone',
                'code'=> $rand
            ],
        ],200);
    }

    /**
     * @OA\Post(
     * path="/api/confirmPhoneLoginCode",
     * summary="confirmPhoneLoginCode",
     * description=" Отправлаете Код потверждения и омер телефонна  каторый  пользватель ввёл  во время входа",
     * operationId="confirmPhoneLoginCode",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="phone", type="string", format="text", example="+37493073584"),
     *       @OA\Property(property="phone_code", type="string", format="text", example="12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description=" User Login  For Phone",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */


    public function confirmPhoneLoginCode(Request $request){
        $rules=array(
            'phone' => 'required',
            'phone_code' => 'required|min:4|max:6'
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }
        $getUser = User::where('phone', $request->phone)->where('phone_code', $request->phone_code)->first();
        if($getUser ==null){
            return response()->json([
               'status' => false,
                'message' =>  'Неверный  код потверждения'
            ],422);
        }



            Auth::login($getUser);
            $token = $getUser->createToken('Laravel Password Grant Client')->accessToken;
               User::where('id', auth()->user()->id)->update([
                   'phone_code' => null,
           
               ]);
            

            LoginCount::create([

            ]);
            return response()->json([
               'status' => true,
               'message' => 'Вы успешно  вошли в свой  акаунт',
               'MyToken' => $token,
            ]);

    }


    /**
     * @OA\Post(
     * path="/api/Logout",
     * summary="Logout",
     * description=" User Logout",
     * operationId="Auth User Logout",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description=" User Logout",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */


    public function Logout(Request $request){
        $token = Auth::user()->token();
        $token->revoke();
        return response()->json([
            'status'=>true,
            'data' => [
                'message' =>  'User Logouted',
            ],
        ],200);
    }

    /**
     * @OA\Post(
     * path="/api/Feedback",
     * summary="Feedback",
     * description=" User Create Feedback",
     * operationId="Feedback",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", format="text", example="Arman"),
     *       @OA\Property(property="email", type="string", format="text", example="arman-aleqyan@mail.ru"),
     *       @OA\Property(property="description", type="string", format="text", example="Long Text"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description=" User Login  For Phone",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function Feedback(Request $request){
        $rules=array(
            'name' => 'required',
            'email' => 'required|email',
            'description' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }
        $get = Feedback::where('SenderEmail', $request->email)->first();
        if($get == null){
            Feedback::create([
                'name' => $request->name,
                'SenderEmail' => $request->email,
                'description' => $request->description,
                'status' => 1
            ]);
            return response()->json([
                'status'=>true,
                'data' => [
                    'message' =>  'Feedback Created',
                ],
            ],200);
        }else{
            Feedback::where('SenderEmail', $request->email)->update([
                'status' => 1,
                'description' =>  $request->description
            ]);

            RegisterFeedbackChat::create([
                'feedback_id' => $get->id,
                'senderEmail' => $request->email,
                'message' => $request->description,
                'status' => 1
            ]);

            return response()->json([
                'status'=>true,
                'data' => [
                    'message' =>  'Feedback Created',
                ],
            ],200);
        }
    }

 
}
