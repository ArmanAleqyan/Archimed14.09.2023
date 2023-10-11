<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Couchbase\Exception;
use Illuminate\Http\Request;
use App\Models\HomeService;
use Illuminate\Support\Facades\Http;

class DepartureController extends Controller
{

    /**
     * @OA\Post(
     * path="/api/setDeparture",
     * summary="",
     * description="set departure",
     * operationId="setDeparture",
     * tags={"Departure"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Add review to services by order",
     *    @OA\JsonContent(
     *       required={"address","description","poligone",},
     *     @OA\Property(property="address", type="string", format="string", example="Abovyan 2"),
     *     @OA\Property(property="description", type="string", format="text", example="nkaragrutyun"),
     *     @OA\Property(property="poligone", type="number", format="number", example="msk")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Add product to bascet",
     *    @OA\JsonContent(
     *        )
     *     ),
     * @OA\Response(
     *    response=402,
     *    description="Add product to bascet",
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
    public function setDeparture(Request $request){
        $user_id = auth()->user()->id;

        $address = $request->address;
        $description = $request->description;
        $poligone = $request->poligone;

        $result = HomeService::where('REGION',$poligone)->first();

        if($result){
            return response()->json([
                'status' => true,
                'data' =>  $result,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'have not this poligone',
            ]);
        }
    }

    /**
     * @OA\Post(
     * path="/api/get_free_time_for_services",
     * summary="",
     * description="get free time for services",
     * operationId="get_free_time_for_services",
     * tags={"Departure"},
     * @OA\RequestBody(
     *    required=true,
     *    description="get free time for services",
     *    @OA\JsonContent(
     *       required={"date_start","date_end","serv_code",},
     *     @OA\Property(property="date_start", type="string", format="string", example="2023-07-24 16:30:00.000"),
     *     @OA\Property(property="date_end", type="string", format="text", example="2023-07-26 16:30:00.000"),
     *     @OA\Property(property="serv_code", type="string", format="string", example="М001К449")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Add product to bascet",
     *    @OA\JsonContent(
     *        )
     *     ),
     * @OA\Response(
     *    response=402,
     *    description="Add product to bascet",
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
    public function get_free_time_for_services(Request $request){

        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $serv_code = $request->serv_code;

        $result = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic EsjUXa4FFU-PqDSmD7S5lw",
        ])->timeout(5000000)->post(env('PAM_URL').'LK_GET_PLAN_TIME_EXAM_INTERVAL_VIEZD',
            [
                "date_start"=>$date_start,
                "date_end"=>$date_end,
                "serv_code"=>$serv_code
            ])->json();

        return response()->json([
           'status'=>true,
           'data'=>$result,
        ]);

    }

    /**
     * @OA\Post(
     * path="/api/get_home_service",
     * summary="",
     * description="Получение home_service",
     * operationId="get_home_service",
     * tags={"Departure"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Получение home_service",
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="get_home_service",
     *    @OA\JsonContent(
     *        )
     *     ),
     * @OA\Response(
     *    response=402,
     *    description="get_home_service",
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
    public function get_home_service(){
        $home_service = HomeService::all();

        return response()->json([
            'status'=>true,
            'data'=>$home_service,
        ],200);
    }
}
