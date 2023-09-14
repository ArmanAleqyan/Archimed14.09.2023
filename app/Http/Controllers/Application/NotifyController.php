<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PamNotification;

class NotifyController extends Controller
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
    public function get_my_notyfy(){
        $get_notification = PamNotification::simplePaginate(10);

        return response()->json([
            'status' => true,
            'data' => $get_notification,
        ],200);
    }



}