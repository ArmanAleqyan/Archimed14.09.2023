<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/get_news",
     *     summary="Get News",
     *     tags={"News"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="data"))
     *         )
     *     )
     * )
     */

    public function get_news(Request $request){
        $get_news = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(5000000)->get('https://arhimed.clinic/api_news/get.php',
            [
                'token' => 'c32D33eSDmf3de77eSa36d0Utd'
            ])->json();


        return response()->json([
           'status' => true,
           'data' => array_slice($get_news['result'], $request->offset,1)
        ]);
    }
}
