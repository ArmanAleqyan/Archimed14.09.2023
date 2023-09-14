<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StartInfo;

class StartInfoController extends Controller
{

    /**
     * @OA\Get(
     * path="/api/StartInfo",
     * summary="StartInfo",
     * description=" ",
     * operationId="StartInfo",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Возвращает текст первых 4 х страниц",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */

    public function StartInfo(){
       $start =  StartInfo::First();
       if($start == null){
           return response()->json([
               'status'=>false,
               'data' => [
                   'message' =>  'no text in show',
               ],
           ],422);
       }
        return response()->json([
            'status'=>true,
            'data' => [
                'text' =>  $start,
            ],
        ],200);

    }
}
