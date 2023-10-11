<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use App\Models\City;
use App\Models\SendCallValidation;
use App\Models\UserAddNewCity;
use App\Models\Clinics;
use App\Models\UserCallCount;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

use Illuminate\Validation\Rule;
use Validator;
use GreenSMS\GreenSMS;
use GuzzleHttp\Client;



class RegisterController extends Controller
{


    public function validation_register(Request $request){

        $rules=array(
            'phone' => [
                'required',
                Rule::unique((new User)->getTable())->where(function ($query) {
                    $query->where('phone_veryfi_code', 1);
                }),
            ],
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return \response()->json([
                'status' => false
            ],401);
        }else{
            return \response()->json([
               'status' => true
            ],200);
        }

    }

    /**
     * @OA\Post(
     * path="/api/register",
     * summary="register",
     * description="Register new user ",
     * operationId="register",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="phone", type="string", format="text", example="+7(999)999-99-99"),
     *       @OA\Property(property="lastName", type="string", format="text", example="Aleqyan"),
     *       @OA\Property(property="firstName", type="string", format="text", example="Arman"),
     *       @OA\Property(property="middleName", type="string", format="text", example="Alberti"),
     *       @OA\Property(property="birthDate", type="string", format="text", example="19970915"),
     *       @OA\Property(property="gender", type="string", format="text", example="M OR F"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="RepeatTheCall User phone",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */


    public function register(User $user, Request $request){

        $rules=array(
               'phone' => [
                   'required',
                   Rule::unique((new User)->getTable())->where(function ($query) {
                       $query->where('phone_veryfi_code', 1);
                   }),
               ],

            'lastName' => 'required|max:254',
            'firstName' => 'required|max:254',
            'middleName' => 'required|max:254',
            'gender' => 'required|max:254',
            'birthDate' => 'max:254|date',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }

        $items = random_int(1000000, 99999999);

        $random_int = random_int(100000,999999);

        $password = strval($items);

        $client = new Client();

        $login = env('sms_login');
        $password_sms = env('sms_password');
        $response = $client->request('GET', 'https://smsc.ru/sys/send.php', [
            'query' => [
                'login' => $login,
                'psw' => $password_sms,
                'phones' => $request->phone,
                'sender' => 'Arman',
                'mes' => "Ваш код подтверждения $random_int",
            ],
        ]);

        $lastName = $request->lastName??"default";
        $firstName = $request->firstName??"default";
        $middleName = $request->middleName??"default";
        $gender = $request->gender??"default";
        $birthDate =  Carbon::parse($request->birthDate)->format('Y-m-d')??"2000-01-01";

                User::updateOrcreate(['phone' =>  $request->phone],[
                    'phone' =>  $request->phone,
                    'phone_veryfi_code' =>  $random_int,
                    'password' =>  Hash::make($password),
                    'user_key' => $password,
                    "lastName" =>  $lastName,
                    "firstName"=>  $firstName,
                    "middleName"=> $middleName,
                    "gender" => $gender,
                    "birthDate" => $birthDate,
                    "role_id" => 3,
                    'email'  => $request->mail
                ]);

                return response()->json([
                'status' => true,
                'message' => "User Created Succsesfuly , code send your Phone number",
                'ClientMessage' => $response,
                'code' => $random_int
                ]);

    }

    /**
     * @OA\Post(
     * path="/api/confirmNewAccount",
     * summary="confirmNewAccount",
     * description="Потвержедение нового пользвателя",
     * operationId="confirmNewAccount",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="phone", type="string", format="text", example="+7(999)999-99-99"),
     *       @OA\Property(property="confirmationCode", type="string", format="text", example="12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="RepeatTheCall User phone",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */

    public function confirmNewAccount(Request $request){
        $rules=array(
            'phone' => 'required',
           'confirmationCode' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }
        $get_user = User::where('phone', $request->phone)->where('phone_veryfi_code', $request->confirmationCode)->first();


        if ($get_user == null){
            return \response()->json([
               'status' => false,
               'message' => 'wrong code'
            ],422);
        }

        $client = new Client();
        $client_token =  env('token');
        $response = $client->request('POST', 'https://apitest.arhimedlab.com/LK_PATIENT_ACTIVATION', [
            'headers' => [
                'Authorization' => "Basic $client_token",
            ],
            'json' => [
                'TEL' => $get_user->phone,
                'NOM' => $get_user->middleName,
                'PRENOM' => $get_user->lastName,
                'PATRONYME' => $get_user->firstName ,
                'NE_LE' => $get_user->birthDate,
                'POL' =>  $get_user->gender,
                'Citizenship' => 'RUS',
            ],
        ]);

        $patientsId = Arr::get(json_decode($response->getBody(), true), 'result.0.PATIENTS_ID');

        

                Auth::login($get_user);
                $token = $get_user->createToken('Laravel Password Grant Client')->accessToken;
                User::where('id', auth()->user()->id)->update([
                    'phone_veryfi_code' => 1,
                    'client_id' => $patientsId
                ]);

                return \response()->json([
                    'status' => true,
                    'message' =>  'Вы успешно прошли верификацыю',
                    'MyToken' => $token,
                    'user' => $get_user
                ],200);
    }


    /**
     * @OA\Post(
     * path="/api/requestNewAccountConfirmationCode",
     * summary="requestNewAccountConfirmationCode",
     * description="Запрос нового кода подтверждения учетной записи",
     * operationId="requestNewAccountConfirmationCode",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="phone", type="string", format="text", example="+7(999)999-99-99"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="RepeatTheCall User phone",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */

    public function requestNewAccountConfirmationCode(Request $request){
        $rules=array(
            'phone' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }


        $getCall =   SendCallValidation::where('phone', $request->phone)->where('type', 'register')->where('created_at', '>', Carbon::now()->subMinutes(10))->get();


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
                'type' => 'register'
            ]);
        }

        if($getCall->count() > 3){
            return response()->json([
               'status' => false,
               'message' => 'try again in 10 minutes'
            ], 422);
        }else{
            SendCallValidation::create([
               'phone' =>  $request->phone,
                'type' => 'register'
            ]);
        }



        $get_user = User::where('phone', $request->phone)->first();
        $random = random_int(100000,999999);
        if ($get_user == null){
           return \response()->json([
              'status' => false,
              'message' => 'wrong phone'
           ],422);
        }else{
            $client = new Client();

            $login = env('sms_login');
            $password_sms = env('sms_password');
            $response = $client->request('GET', 'https://smsc.ru/sys/send.php', [
                'query' => [
                    'login' => $login,
                    'psw' => $password_sms,
                    'phones' => $request->phone,
                    'sender' => 'Arman',
                    'mes' => "Ваш код подтверждения $random",
                ],
            ]);

            $get_user->update([
               'phone_veryfi_code' =>  $random
            ]);
        }
            return \response()->json([
                'status' => true,
                'message' => 'code sendet yor phone '
            ],200);

    }



    public function GeoAccess(Request $request){
        $rules=array(
            'geo_access' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }
        $geo = $request->geo_access;
        if($geo != 1 && $geo != 2 && $geo != 3){
            return response()->json([
                'status'=>false,
                'data' => [
                    'message' =>  'Required 1 Or 2 Or 3',
                ],
            ],422);
        }

        $user_id = auth()->guard('api')->user()->id;

        User::where('id', $user_id)->update([
           'geo_dostup' => $request->geo_access
        ]);
        return response()->json([
            'status'=>true,
            'data' => [
                'message' =>  'Geo Status  Saved',
            ],
        ],200);
    }


    /**
     * @OA\Get(
     * path="/api/GetCity",
     * summary="GetCity",
     * description=" Return List City",
     * operationId="GetCity",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="  Return List City",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */

    public function GetCity(){


        $get_city = Clinics::get(['id', 'ville']);

        return response()->json([
            'status'=>true,
            'data' => [
                'city' =>  $get_city,
            ],
        ],200);
    }
    /**
     * @OA\Post(
     * path="/api/UserAddCity",
     * summary="UserAddCity",
     * description=" Выберите город из api GetCity ",
     * operationId="UserAddCity",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="city_id", type="string", format="text", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description=" Выберите город из api GetCity",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function UserAddCity(Request $request){
        $rules=array(
            'city_id' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }
        $get_city = Clinics::where('id', $request->city_id)->first();
        if($get_city == null){
            return response()->json([
                'status'=>false,
                'data' => [
                    'message' =>  'Wrong City ID',
                ],
            ],422);
        }

        User::where('id', auth()->guard('api')->user()->id)->update([
           'city_id' => $request->city_id,
           'filial_id' => $get_city->clinic_id,
           'city_name' => $get_city->ville
        ]);
        return response()->json([
            'status'=>true,
            'data' => [
                'message' =>  'User added city',
            ],
        ],200);
    }


//    /**
//     * @OA\Post(
//     * path="/api/PersonalInformation",
//     * summary="PersonalInformation",
//     * description=" User Add Personal Information",
//     * operationId="PersonalInformation",
//     * tags={"Auth"},
//     * @OA\RequestBody(
//     *    required=true,
//     *    @OA\JsonContent(
//     *       @OA\Property(property="name", type="string", format="text", example="Arman"),
//     *       @OA\Property(property="surname", type="string", format="text", example="Aleqyan"),
//     *       @OA\Property(property="middle_name", type="string", format="text", example="Alberti"),
//     *       @OA\Property(property="gender", type="string", format="text", example="Male"),
//     *       @OA\Property(property="date_of_birth", type="string", format="text", example="15.09.1997"),
//     *       @OA\Property(property="email", type="string", format="text", example="arman-aleqyan@mail.ru"),
//     *    ),
//     * ),
//     * @OA\Response(
//     *    response=200,
//     *    description=" User Add Personal Information",
//     *    @OA\JsonContent(
//
//     *        )
//     *     )
//     * )
//     */
//
//    public function PersonalInformation(Request $request){
//        $rules=array(
//            'name' => 'required',
//            'surname' => 'required',
//            'middle_name' => 'required',
//            'gender' => 'required',
//            'date_of_birth' => 'required',
//            'email' => 'required|unique:users|email',
//        );
//        $validator=Validator::make($request->all(),$rules);
//        if($validator->fails())
//        {
//            return $validator->errors();
//        }
//        $time = strtotime($request->date_of_birth);
//        $newformat = date('Y-m-d',$time);
//        User::where('id', auth()->user()->id)->update([
//           'name' => $request->name,
//           'surname' => $request->surname,
//           'middle_name' => $request->middle_name,
//           'gender' => $request->gender,
//           'date_of_birth' => $newformat,
//           'email' => $request->email
//        ]);
//        return response()->json([
//            'status'=>true,
//            'data' => [
//                'message' =>  'User added Personal Information',
//            ],
//        ],200);
//    }


    /**
     * @OA\Post(
     * path="/api/UserAddNewCity",
     * summary="UserAddNewCity",
     * description=" User Add New City Name",
     * operationId="UserAddNewCity",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="city", type="string", format="text", example="Arman"),
     *       @OA\Property(property="user_email", type="string", format="text", example="user email"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="User Add New City Name",
     *    @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function UserAddNewCity(Request $request){
        $rules=array(
            'city' => 'required',
          //  'user_email' => 'required'
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }
        $Get_new = UserAddNewCity::where('user_id', auth()->guard('api')->user()->id)->first();
        User::where('id', auth()->guard('api')->user()->id )->update([
            'city_id' => 1,
            'city_name' => 'Москва'
        ]);
        if($Get_new != null){
            return response()->json([
                'status'=>false,
                'data' => [
                    'message' =>  'User Already Added  City',
                ],
            ],422);
        }

        UserAddNewCity::create([
           'user_id' => auth()->guard('api')->user()->id,
           'city' => $request->city,
        ]);

        return response()->json([
            'status'=>true,
            'data' => [
                'message' =>  'User Added New City',
            ],
        ],200);
    }





}
