<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalTest;
use App\Models\MedicalTestParametr;

class MedicalTestController extends Controller
{


    /**
     * @OA\Post(
     * path="/api/medical_test",
     * summary="medical_test",
     * description="Получаем Список Комплексов анализа",
     * operationId="MedicalTest",
     * tags={"MedicalTest"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="search", type="string", format="text", example="search"),
     *       @OA\Property(property="complex", type="string", format="text", example="true"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Test Data",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
    public function medical_test(Request $request){
        if ($request->complex == true){
            $get = MedicalTest::query();
        }else{
            $get = MedicalTest::query();
        }
        if (isset($request->search)){
            $name_parts = explode(' ', $request->search);
            foreach ($name_parts as $part){
                $get->orWhere(function ($query) use ($part) {
                    $query
                        ->where('NAME', 'like', "%{$part}%")
                        ->orwhere('PRINT_MEMO', 'like', "%{$part}%");
                });
            }
        }
        if ($request->complex == true){
            $get =   $get->where('GROUP_DIR','Комплекс')->simplepaginate(15);
        }else{
            $get =   $get->simplepaginate(15);
        }
        return response()->json([
           'status' => true,
           'data' => $get
        ],200);
    }

    /**
     * @OA\Post(
     * path="/api/medical_test_params",
     * summary="medical_test_params",
     * description="Получаем Список Анализов  конкретного комплекса",
     * operationId="medical_test_params",
     * tags={"MedicalTest"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="search", type="string", format="text", example="search"),
     *       @OA\Property(property="test_id", type="string", format="text", example="39"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Test Analize Data",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */

    public function medical_test_params(Request $request){

     $get  =    MedicalTestParametr::query();
     $get_tests =  MedicalTest::where('id', $request->test_id)->first();


        if (isset($request->search)){
            $name_parts = explode(' ', $request->search);
            foreach ($name_parts as $part){
                $get->orWhere(function ($query) use ($part) {
                    $query
                        ->where('CODE', 'like', "%{$part}%")
                        ->orwhere('LABEL', 'like', "%{$part}%")
                        ->orwhere('biotype', 'like', "%{$part}%")
                        ->orwhere('conttype', 'like', "%{$part}%")
                        ->orwhere('price', 'like', "%{$part}%");
                });
            }
        }

        \App\Models\AnalizePageShow::create([
            'analise_name' => $get_tests->NAME
        ]);
        $get =   $get->where('test_id', $request->test_id)->simplepaginate(15);


        return response()->json([
            'status' => true,
            'data' => $get,
            'parent_test' => $get_tests
        ],200);
    }


    /**
     * @OA\Post(
     *     path="/api/medical_test_params_single_page",
     *     summary="Get medical test parameters for a single page",
     *      tags={"MedicalTest"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="test_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Wrong test_id",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Wrong test_id")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="LABEL", type="string"),
     *             )
     *         )
     *     )
     * )
     */

    public function medical_test_params_single_page(Request $request){
       // return $request->test_id;
        $get_tests =  MedicalTestParametr::where('id', $request->test_id)->first();
        if ($get_tests == null){
            return response()->json([
               'status' => true,
               'message' => 'Wrong test_id'
            ],422);
        }

        \App\Models\AnalizePageShow::create([
            'analise_name' => $get_tests->LABEL
        ]);

        return response()->json([
            'status' => true,
            'data' => $get_tests
        ],200);
    }
}
