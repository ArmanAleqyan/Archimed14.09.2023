<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\MedicalTestParametr;
class AnaliseAndServices extends Controller
{
    /**
     * @OA\Get(
     * path="/api/get_analise_and_service",
     * summary="get_basket",
     * description="Получение данных из цветка анализов",
     * operationId="get_analise_and_service",
     * tags={"Analise and service"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Получение данных из цветка анализов",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
    public function get_analise_and_service(){
        $client_token = env('token');

        $result = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $client_token",
        ])->timeout(5000000)->post(env('PAM_URL').'VIEW_LK_SERV',
            [

            ])->json();

        $collection = collect($result['result']);
        $firstTen = $collection->take(10);



        $get = MedicalTestParametr::wherein('CODE', $firstTen->pluck('CODE'))->get();

        return response()->json([
            'status'=>true,
            'data'=>$get
        ],200);
    }


}
