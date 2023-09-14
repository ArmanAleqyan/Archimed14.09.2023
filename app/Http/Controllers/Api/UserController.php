<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/auth_user_info",
     *     summary="Get authenticated user information",
     *     description="Returns information about the authenticated user.",
     *     operationId="authUserInfo",
     *     tags={"MyCabinet"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="data", type="object", ref="user_data")
     *         )
     *     ),
     *     security={{"bearer_token":{}}}
     * )
     */

    public function auth_user_info(){

        $user = auth()->user();


        return response()->json([
           'status' => true,
           'data' => $user
        ],200);
    }


    /**
     * @OA\Post(
     *     path="/api/update_user_info",
     *     summary="Update user information",
     *     tags={"MyCabinet"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="GRAGDANSTVO", type="string", maxLength=254, nullable=true),
     *                 @OA\Property(property="FACT_ADRES", type="string", maxLength=254, nullable=true),
     *                 @OA\Property(property="JOB", type="string", maxLength=254, nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User information updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="updated")
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid data")
     *         )
     *     ),
     *     security={
     *         {"Bearer": {}}
     *     }
     * )
     */



    public function update_user_info(Request $request){
        $rules=array(
            'GRAGDANSTVO' => 'max:254',
            'FACT_ADRES' => 'max:254',
            'JOB' => 'max:254',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }
        if (isset($request->GRAGDANSTVO)){
            auth()->user()->update([
                'Citizenship' => $request->GRAGDANSTVO
            ]);
            $data['GRAGDANSTVO'] = $request->GRAGDANSTVO;
        }
        if (isset($request->FACT_ADRES)) {
            auth()->user()->update([
                'Actual_Address' => $request->FACT_ADRES
            ]);
            $data['FACT_ADRES']= $request->FACT_ADRES;
        }
            if (isset($request->JOB)){
                auth()->user()->update([
                   'job' => $request->JOB
                ]);
                $data['JOB'] =
                   $request->JOB
                ;
            }

            $data['patients_id'] = auth()->user()->client_id;

        $curl = curl_init();

        $jsonData = json_encode($data);

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('PAM_URL').'LK_PATIENT_CHANGE',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic EsjUXa4FFU-PqDSmD7S5lw',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        return response()->json([
           'status' => true,
           'message' => 'updated'
        ],200);
        }

}
