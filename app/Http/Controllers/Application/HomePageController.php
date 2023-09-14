<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomePageController extends Controller
{


//    /**
//     * @OA\Get(
//     * path="/api/HomePageApp",
//     * summary="HomePageApp",
//     * description="Возвращает все данные для Homepage",
//     * operationId="HomePageApp",
//     * tags={"Home"},
//     * @OA\RequestBody(
//     *    required=true,
//     *    @OA\JsonContent(
//     *    ),
//     * ),
//     * @OA\Response(
//     *    response=200,
//     *    description="HomePageApp",
//     *    @OA\JsonContent(
//
//     *        )
//     *     )
//     * )
//     */
//
//
//    public function HomePageApp(Request $request){
//
//        $user = Http::withHeaders([
//            'Content-Type' => 'application/json',
//            'app-code' => env('Medialog_Code'),
//            'Authorization' => 'Bearer '.auth()->guard('api')->user()->client_token,
//        ])->get('https://dev.mobimed.ru/Telemedialog/CentralService/user/info',
//            [
//            ])->json();
//
//        $notificatioList = Http::withHeaders([
//            'Content-Type' => 'application/json',
//            'app-code' => env('Medialog_Code'),
//            'Authorization' => 'Bearer '.auth()->guard('api')->user()->client_token,
//        ])->post('https://dev.mobimed.ru/Telemedialog/CentralService/notification/list',
//            [
//                "mmkId" => "string",
//                  "clinicId"  => 179,
//                  "readState" => "Unread",
//                  "startIndex" => 0,
//                  "count" => 3,
//                  "typeFilter" => [
//                            "string"
//                        ],
//                  "searchValue" => "string"
//            ])->json();
//
//        $Directions = Http::withHeaders([
//            'Content-Type' => 'application/json',
//            'app-code' => env('Medialog_Code'),
//            'Authorization' => 'Bearer '.auth()->guard('api')->user()->client_token,
//        ])->get('https://dev.mobimed.ru/Telemedialog/CentralService/mmk/directions',
//            [
//                "allAccess" => "true",
//            ])->json();
//
//        $visitList = Http::withHeaders([
//            'Content-Type' => 'application/json',
//            'app-code' => env('Medialog_Code'),
//            'Authorization' => 'Bearer '.'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOjI3MjIzNCwicm9sZSI6MSwiYXBwQ29kZSI6ImNvbS5wb3N0bW9kZXJuLm1vYmltZWRSZWFjdCIsIm1lZGljYWxOZXRJZCI6MTUsImxhbmdDb2RlIjoicnVzIiwiaWF0IjoxNjcwMzE0MjQzLCJleHAiOjE3MDE4NTAyNDMsImF1ZCI6ImFwaSIsImlzcyI6Ii9hdXRoL2xvZ2luIiwic3ViIjoiMjA5LjI1MC4yNDEuMTciLCJqdGkiOiJkZmM2MDY1OC1iZjljLTRlZjAtYTc1MC00YjdlZmZkNDU4ZGMifQ.K87NuFDx471hfb7JtSRPUXk6H2UGj1gRvCy45OhnXEo1ZUl-q8_Hz4ByOw-eoNM2_Nj27F7lDnLZYvr72jAFeTYKBRSBQbsilzg9oWDt4P-08yRInmeHkekWpgfvbrQTb_M1nedISRpk_plGm8xValc7ZYBMqU_jKbNXS7TkJ1qJGxdtjNXrM3xw4ekg0dTjt8i9WqI5bbVFnn2uCaruO14O4V1Uq2BB86uTZmJQSn2DtjK6bTEKcDASDIH2F2hBKxEZ6fmfWdycQs2E8oTDV0toyDFpjj6RVX21n-MpSCeTmgUFWSNfUeniTmrlpJL1dgnWFiXiqYj0zY2KwsOk3Q'
//           //'Authorization' => 'Bearer '.auth()->guard('api')->user()->client_token
//            ,
//        ])->get('https://dev.mobimed.ru/Telemedialog/CentralService/visit/list',
//            [
//               'futureVisits' =>  'true',
//                'startIndex' => 0,
//                'count' => 2,
//
//               'recordStatus' =>  'Active'
//            ])->json();
//
//
//
//
//        $news = Http::withHeaders([
//            'Content-Type' => 'application/json',
//        ])->get(   env('APP_URL').'api/NewsAndSales',
//            [
//            ])->json();
//
//        $info = Http::withHeaders([
//            'Content-Type' => 'application/json',
//        ])->get(   env('APP_URL').'api/InfoArchimedMedical',
//            [
//            ])->json();
//
//       return response()->json([
//          'status' => true,
//          'user' => $user,
//          'notifications' =>  $notificatioList,
//           'AnaliziAndObsedovanieAndZapisi' => $visitList,
//           'Directions' => $Directions,
//           'news' => $news,
//           'info' =>  $info
//
//       ]);
//    }
}

