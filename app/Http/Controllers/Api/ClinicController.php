<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clinics;

class ClinicController extends Controller
{

    /**
     * @OA\Get(
     * path="/api/get_clinics",
     * summary="",
     * description="get clinics",
     * operationId="get_clinics",
     * tags={"Clinic"},
     * @OA\RequestBody(
     *    required=true,
     *    description="get clinics",
     * ),
     * @OA\Response(
     *    response=200,
     *    description="get clinics",
     *    @OA\JsonContent(
     *        )
     *     ),
     * @OA\Response(
     *    response=402,
     *    description="get clinics",
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
    public function get_clinics(){
        $clinics = Clinics::all();

        return response()->json([
            'status'=>true,
            'data'=>$clinics,
        ],200);
    }

    /**
     * @OA\Post(
     * path="/api/get_clinics_by_id",
     * summary="",
     * description="get clinics by id",
     * operationId="get_clinics_by_id",
     * tags={"Clinic"},
     * @OA\RequestBody(
     *    required=true,
     *    description="get clinics by id",
     *    @OA\JsonContent(
     *       required={"clinic_id"},
     *     @OA\Property(property="clinic_id", type="number", format="number", example="12"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="get clinics by id",
     *    @OA\JsonContent(
     *        )
     *     ),
     * @OA\Response(
     *    response=402,
     *    description="get clinics by id",
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
    public function get_clinics_by_id(Request $request){
        $clinic = Clinics::find($request->clinic_id);

        return response()->json([
            'status'=>true,
            'data'=>$clinic,
        ],200);
    }

    /**
     * @OA\Get(
     * path="/api/get_Moscow_clinic_phone",
     * summary="",
     * description="Получение телефона клиники в Москве",
     * operationId="get_Moscow_clinic_phone",
     * tags={"Clinic"},
     * @OA\RequestBody(
     *    required=true,
     *    description="get clinics",
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Получение телефона клиники в Москве",
     *    @OA\JsonContent(
     *        )
     *     ),
     * @OA\Response(
     *    response=402,
     *    description="get clinics",
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
    public function get_Moscow_clinic_phone(){
        $clinic = Clinics::select('inn')->where('ville','Москва')->get();

        return response()->json([
            'status'=>true,
            'data'=>$clinic,
        ],200);
    }
}
