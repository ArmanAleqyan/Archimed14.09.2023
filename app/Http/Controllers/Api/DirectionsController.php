<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Basket;

class DirectionsController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/get_directions",
     * summary="",
     * description="get directions",
     * operationId="get_directions",
     * tags={"Direction"},
     * @OA\RequestBody(
     *    required=true,
     *    description="get directions",
     * ),
     * @OA\Response(
     *    response=200,
     *    description="get directions",
     *    @OA\JsonContent(
     *        )
     *     ),
     * @OA\Response(
     *    response=402,
     *    description="get directions",
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
    //направления
    public function get_directions(Request $request){
        $user_id = auth()->user()->id;
        $patients_id =  Basket::select('client_id')->where('user_id',$user_id)->first();

        $result = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic EsjUXa4FFU-PqDSmD7S5lw",
        ])->timeout(5000000)->post(env('PAM_URL').'VIEW_LK_GET_PATIENTS_PATDIR',
            [
                "patients_id"=>$patients_id->client_id,
            ])->json();

        return response()->json([
            'status'=>true,
            'data'=>$result
        ],200);
    }
}
