<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutAnalise;

class AboutAnaliseController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/get_about_analise_info",
     * summary="get_about_analise_info",
     * description="Получение текста под цветком анализов",
     * operationId="get_about_analise_info",
     * tags={"AboutAnalise"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Получение текста под цветком анализов",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
    public function get_about_analise_info(){
        $result = AboutAnalise::first();

        return response()->json([
            'status'=>true,
            'data'=>$result->about_analise,
        ],200);
    }
}
