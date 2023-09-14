<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginCount;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AppleRegisterAndLoginController extends Controller
{

    /**
     * @OA\Post(
     * path="/api/RegisterFromApple",
     * summary="RegisterFromApple",
     * description="Сначала отправлаем apple_id и email На втором этапе отправлаем остольное",
     * operationId="Register Apple",
     * tags={"RegisterApple"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="phone", type="string", format="text", example="+7(999)999-99-99"),
     *       @OA\Property(property="lastName", type="string", format="text", example="Aleqyan"),
     *       @OA\Property(property="firstName", type="string", format="text", example="Arman"),
     *       @OA\Property(property="middleName", type="string", format="text", example="Alberti"),
     *       @OA\Property(property="birthDate", type="string", format="text", example="19970915"),
     *       @OA\Property(property="gender", type="string", format="text", example="M OR F"),
     *       @OA\Property(property="email", type="string", format="text", example="appleemail@mail.ru"),
     *       @OA\Property(property="apple_id", type="string", format="text", example="apple_id"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="User Created",
     *    @OA\JsonContent(
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="This Apple Id Exist",
     *    @OA\JsonContent(
     *        )
     *     )
     * )
     */



    public function RegisterFromApple(Request $request){
        $rules=array(
            'apple_id' => 'required',
            'email' => 'required|email',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }
        $get_user = User::where('apple_id',$request->apple_id)->first();
        if($get_user != null && $get_user->firstName != null){
                return response()->json([
                   'status' => false,
                   'message' => 'This Apple Id Exist'
                ],422);
        }elseif ($get_user != null && $get_user->firstName == null){
            $rules=array(
                'apple_id' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'lastName' => 'required',
                'firstName' => 'required',
                'middleName' => 'required',
                'gender' => 'required',
                'birthDate' => 'required',
            );
            $validator=Validator::make($request->all(),$rules);
            if($validator->fails())
            {
                return $validator->errors();
            }
        }
        $items = random_int(1000000, 99999999);
        $password = strval($items);
        User::updateOrcreate(['apple_id' => $request->apple_id],[
                'apple_id' => $request->apple_id,
                'email' => $request->email,
                'password' =>  Hash::make($password),
                   'user_key' => $password,
                   "lastName" =>   $request->lastName,
                   "firstName"=>   $request->firstName,
                   "middleName"=>  $request->middleName,
                   "gender" =>     $request->gender,
                   'email_verify_code' =>1,
                   "birthDate" =>  Carbon::parse($request->birthDate)->format('Y-m-d') ,
                   "role_id" =>    3,

        ]);


        if (isset($request->lastName) && isset($request->firstName)){
            $client = new Client();
            $client_token =  env('token');
            $response = $client->request('POST', 'https://apitest.arhimedlab.com/LK_PATIENT_ACTIVATION', [
                'headers' => [
                    'Authorization' => "Basic $client_token",
                ],
                'json' => [
                    'NOM' => $request->lastName,
                    'PRENOM' => $request->firstName,
                    'PATRONYME' => $request->middleName,
                    'NE_LE' => $request->birthDate,
                    'EMAIL' => $request->email,
                    'POL' =>  $request->gender,
                    'Citizenship' => 'RUS',
                    'TEL' => $request->phone,
                ],
            ]);

            $patientsId = Arr::get(json_decode($response->getBody(), true), 'result.0.PATIENTS_ID');


            User::where('apple_id' , $request->apple_id)->update([
               'client_id' =>  $patientsId,
                'phone' => $request->phone,
                'phone_veryfi_code' => 1
            ]);
        }


        return response()->json([
           'status' => true,
           'message' => 'User Created'
        ],200);
    }



    /**
     * @OA\Post(
     * path="/api/LoginFromApple",
     * summary="LoginFromApple",
     * description="отправлаем apple_id и email",
     * operationId="LoginFromApple",
     * tags={"RegisterApple"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="email", type="string", format="text", example="appleemail@mail.ru"),
     *       @OA\Property(property="apple_id", type="string", format="text", example="apple_id"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Вы успешно  вошли в свой  акаунт",
     *    @OA\JsonContent(
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong Apple Id",
     *    @OA\JsonContent(
     *        )
     *     )
     * )
     */


    public function LoginFromApple(Request $request){
        $rules=array(
            'apple_id' => 'required',
            'email' => 'required|email',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }
        $get_user = User::where('apple_id',$request->apple_id)->first();



        if ($get_user == null){
            return response()->json([
               'status' => false,
               'message' => 'Wrong Apple Id'
            ],422);
        }

        User::where('apple_id',$request->apple_id)->update([
           'email' => $request->email
        ]);

        Auth::login($get_user);
        $token = $get_user->createToken('Laravel Password Grant Client')->accessToken;
        LoginCount::create([

        ]);
        return response()->json([
            'status' => true,
            'message' => 'Вы успешно  вошли в свой  акаунт',
            'MyToken' => $token,
        ],200);


    }
}
