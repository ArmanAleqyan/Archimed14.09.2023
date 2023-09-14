<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsAndSales;
use App\Models\InfoArchimedMedical;

class NewsAndSalesController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/NewsAndSales",
     * summary="NewsAndSales",
     * description="Возращает Новости т акцые  различаютса по column Type",
     * operationId="NewsAndSales",
     * tags={"News"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="success",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function NewsAndSales(){
        $news = NewsAndSales::OrderBy('id', 'Desc')->paginate(10);
        return response()->json([
           'status' => true,
            'data' => $news
            ], 200);
    }

    /**
     * @OA\Get(
     * path="/api/InfoArchimedMedical",
     * summary="InfoArchimedMedical",
     * description="Возращает Информацыю о клиники  ест главные и просто информацыя на главную ставите ",
     * operationId="InfoArchimedMedical",
     * tags={"InfoMedical"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="success",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */

    public function InfoArchimedMedical(){
        $infoHeader = InfoArchimedMedical::where('status', 'Главная')->orderBy('id','Desc')->get();

        if($infoHeader->isEmpty()){
            $infoHeader = 'Empty Info';
        }

        $info = InfoArchimedMedical::OrderBy('id','Desc')->where('status', '!=' , 'Главная')->get();

        if($info->isEmpty()){
            $info = 'Empty info';
        }

        return response()->json([
           'status' => true,
           'Headers' => $infoHeader,
           'info' => $info,
        ],200);
    }
}
