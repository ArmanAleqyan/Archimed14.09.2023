<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Basket;
use Illuminate\Support\Facades\Http;

class TestResultController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/get_analis",
     * summary="get_analis",
     * description="get_analis",
     * operationId="get_analis",
     * tags={"TestResult"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Basket data",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
    public function get_analis(){
        $user_id = auth()->user()->id;

        $orders = Basket::with('get_analis_by_medical_test_parametr')->where('user_id',$user_id)->where('parent_type','App\Models\MedicalTestParametr')->get();
        return response()->json([
            'status'=>true,
            'data'=>$orders,
        ],200);
    }

    /**
     * @OA\Get(
     * path="/api/get_analis_limit3",
     * summary="get_analis_limit3",
     * description="Анализы и обследования лимит 3",
     * operationId="get_analis_limit3",
     * tags={"TestResult"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Анализы и обследования лимит 3",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
    public function get_analis_limit3(){
        $user_id = auth()->user()->id;

        $orders = Basket::with('get_analis_by_medical_test_parametr')
            ->where('user_id',$user_id)
            ->where('parent_type','App\Models\MedicalTestParametr')
            ->orderBy('id','desc')
            ->limit(3)
            ->get();
        return response()->json([
            'status'=>true,
            'data'=>$orders,
        ],200);
    }

    /**
     * @OA\Post(
     * path="/api/get_test_result",
     * summary="",
     * description="get_test_result",
     * operationId="get_test_result",
     * tags={"TestResult"},
     * @OA\RequestBody(
     *    required=true,
     *    description="get_test_result",
     *    @OA\JsonContent(
     *       required={"order_id","service_id","patdirec_id",},
     *     @OA\Property(property="order_id", type="number", format="number", example="123"),
     *     @OA\Property(property="service_id", type="string", format="text", example="1d23"),
     *     @OA\Property(property="patdirec_id", type="number", format="number", example="123")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Add product to bascet",
     *    @OA\JsonContent(
     *        )
     *     ),
     * @OA\Response(
     *    response=402,
     *    description="Add product to bascet",
     *    @OA\JsonContent(
     *        )
     *     ),
     * @OA\Response(
     *    response=500,
     *    description="server error",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
    public function get_test_result(Request $request){
        $user_id = auth()->user()->id;
        $patients_id = Basket::select('client_id')->where('user_id',$user_id)->first();
        $order_id = $request->order_id;
        $service_id = $request->service_id;
        $patdirec_id = false;


        //api for get patdirec_id
        $apiUrl = 'https://apitest.arhimedlab.com/LK_ORDER_INFO';
        $headers = [
            'Authorization: Basic EsjUXa4FFU-PqDSmD7S5lw',
            'Content-Type: application/json',
        ];

        $data = [
            'patients_id' => 120099,
            'order_id' => 80,
            'service_id' => '10c00970',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        curl_close($ch);

        $jsonString = '{"result":[{"order_id":80,"status":"Готово","patdirec_id":5611259}]}';
        $jsonArray = json_decode($jsonString, true);
        $resultArray = $jsonArray["result"];

        if($resultArray[0]){
            $patdirec_id = $resultArray[0]['patdirec_id'];
        }



        ///api for get analise result
        $apiUrl = 'https://apitest.arhimedlab.com/LK_GET_RESULT_BY_PATDIREC';
        $headers = [
            'Authorization: Basic EsjUXa4FFU-PqDSmD7S5lw',
            'Content-Type: application/json',
        ];

        $data = [
            'patdirec_id' => $patdirec_id,
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        curl_close($ch);

        return response()->json([
            'status'=>true,
            'data'=>$response,
        ]);

    }
}
