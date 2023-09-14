<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;use Illuminate\Support\Facades\Http;
use App\Models\User;

class RefreshTokenController extends Controller
{

//    /**
//     * @OA\Post(
//     * path="/api/RefreshClientToken",
//     * summary="RefreshClientToken",
//     * description="Обновлаем Token клиента Отправлаете  мой  токен  ",
//     * operationId="RefreshClientToken",
//     * tags={"Auth"},
//     * @OA\RequestBody(
//     *    required=true,
//     *    @OA\JsonContent(
//     *    ),
//     * ),
//     * @OA\Response(
//     *    response=200,
//     *    description="RefreshClientToken",
//     *    @OA\JsonContent(
//
//     *        )
//     *     )
//     * )
//     */
//    public function RefreshClientToken(){
//        $token = Http::withHeaders([
//            'Content-Type' => 'application/json',
//            'app-code' => env('Medialog_Code'),
//            'Authorization' => 'Bearer '.auth()->guard('api')->user()->client_token,
//        ])->post('https://dev.mobimed.ru/Telemedialog/CentralService/auth/refreshToken',
//            [
//                'authToken' => auth()->guard('api')->user()->client_token,
//                'refreshToken' => auth()->guard('api')->user()->client_refresh_token
//            ])->json();
//
//
//
//        User::where('id',  auth()->user()->id)->update([
//            'client_token' => $token['authToken'],
//            'client_refresh_token' =>$token['refreshToken']
//        ]);
//
//
//        return response()->json([
//           'status' =>  true,
//           'client_token' =>  auth()->guard('api')->user()->client_token,
//            'client_refresh_token' => auth()->guard('api')->user()->client_refresh_token
//        ],200);
//    }

}
