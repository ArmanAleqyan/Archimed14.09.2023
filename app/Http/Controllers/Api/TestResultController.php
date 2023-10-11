<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Basket;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use App\Models\MedicalTestParametr;
use App\Models\OtvetNaAnaliz;
use App\Models\OtvetNaAnalizPdf;
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
    public function get_test_result(){
//        $user_id = auth()->user()->id;
        $token = env('token');
//        $patients_id = Basket::select('client_id')->where('user_id',$user_id)->first();
//        $order_id = $request->order_id;
//        $service_id = $request->service_id;
//        $patdirec_id = false;


        $get_users = User::where('client_id', '!=', null)->get('id')->pluck('id')->toarray();



        $get_basket = Basket::wherein('user_id', $get_users)->where('status', 3)->get();

        $headers = [
            "Authorization: Basic $token",
            'Content-Type: application/json',
        ];

        if (!$get_basket->isempty()){

            foreach ($get_basket as $basket){
                if ($basket->parent_type == 'App\Models\MedicalTestParametr'){
                    $get_medical_test_parametr = MedicalTestParametr::where('id', $basket->parent_id)->first();
                    $apiUrl = 'https://apitest.arhimedlab.com/LK_ORDER_INFO';


                    $data = [
                        'patients_id' => $basket->client_id,
                        'order_id' => $basket->client_order_id,
                        'service_id' => $get_medical_test_parametr->CODE??null,
                    ];
//                    $data =[
//                        'patients_id' => 120099,
//                        'order_id' => 80,
//                        'service_id' => '10c00970',
//                    ];

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
                    if ($response == '{"result":[{"error":"Ошибка получения статуса заказа"}]}'){
                    }else{
                        $jsonString = $response;


                        $parts = explode(',', $jsonString);

                        $data = [];

                        foreach ($parts as $part) {
                            $keyValue = explode(':', $part, 2);
                            $key = trim(trim($keyValue[0]), '"');
                            $value = trim(trim($keyValue[1]), '"');
                            $data[$key] = $value;
                        }


                        $dataString = str_replace('}]}', '', $data['patdirec_id']);


//                        $jsonStrings = '{"result":[{"order_id":80,"status":"Готово","patdirec_id":5611259}]}';

                        $jsonArray = json_decode($jsonString, true);


//                        $resultArray = $jsonArray["result"];
//                        if($resultArray[0]){
//                            $patdirec_id = $resultArray[0]['patdirec_id'];
//                        }
                        ///api for get analise result
                        $apiUrl = 'https://apitest.arhimedlab.com/LK_GET_RESULT_BY_PATDIREC';
                        $headers = [
                            "Authorization: Basic $token",
                            'Content-Type: application/json',
                        ];

                        $data = [
                            'patdirec_id' => $dataString,
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
                        $data = json_decode($response, true);

                        foreach ($data as $otvet_na_analizs){

                            foreach ($otvet_na_analizs as $otvet_na_analiz) {

                                $create =  OtvetNaAnaliz::updateOrCreate(['client_db_id' => $otvet_na_analiz['id'],  'user_id' =>  $basket->user_id],[
                                    'user_id' =>  $basket->user_id??null,
                                    'client_id' => $otvet_na_analiz['patients_id']??null,
                                    'exam_name' => $otvet_na_analiz['exam_name']??null,
                                    'patdirec_id' => $otvet_na_analiz['patdirec_id']??null,
                                    'Res_date' => $otvet_na_analiz['Res_date']??null,
                                    'Date_bio' => $otvet_na_analiz['Date_bio']??null,
                                    'FIO_patient' => $otvet_na_analiz['FIO_patient']??null,
                                    'BD_patients' => $otvet_na_analiz['BD_patients']??null,
                                    'Sex_patient' => $otvet_na_analiz['Sex_patient']??null,
                                    'TYPE' => $otvet_na_analiz['TYPE']??null,
                                    'client_db_id' => $otvet_na_analiz['id']??null,
                                    'basket_id' => $basket->id
                                 ]);

                                if (isset($otvet_na_analiz['PDF'])){
                                    foreach ($otvet_na_analiz['PDF'] as $pdf) {
                                            OtvetNaAnalizPdf::updateOrCreate(['otvet_id' => $create->id],[
                                                'otvet_id' => $create->id,
                                                'FileName' => $pdf['FileName'],
                                                'PDF' => $pdf['PDF'],
                                            ]);
                                    }
                                }
                           }



                        }

                        $basket->update([
                           'status' => 5
                        ]);
                    }

                }
            }
            }


        return response()->json([
            'status'=>true,
            'data'=>$data??[]
        ]);

    }
}
