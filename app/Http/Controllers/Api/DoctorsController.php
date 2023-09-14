<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoctorList;
use App\Models\DoctorService;
use Illuminate\Support\Facades\Http;
use Validator;

class DoctorsController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/doctors_list",
     * summary="doctors_list",
     * description="Получаем Список врачей",
     * operationId="doctors_list",
     * tags={"Doctors"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="city", type="string", format="text", example="Москва"),
     *       @OA\Property(property="orderby", type="string", format="text", example="true"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Doctors Data",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */


    public function doctors_list(Request $request){

        $get = DoctorList::query();

        if (isset($request->city)){
            $get->Whererelation('DoctorsSubject', 'VILLE', $request->city);
        }

        if (isset($request->orderby) && $request->orderby == true ){
            $get->orderbY('FIO', 'ASC');
        }
        $doctors = $get->with('DoctorsSubject','DoctorService')->simplepaginate(10);

        return response()->json([
           'status' => true,
           'data' => $doctors
        ],200);
    }

    /**
     * @OA\Post(
     * path="/api/single_doctor",
     * summary="single_doctor",
     * description="Получаем Одного врача",
     * operationId="single_doctor",
     * tags={"Doctors"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="doctor_id", type="string", format="text", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Doctor Data",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
    public function single_doctor(Request $request){

        $get = DoctorList::where('id', $request->doctor_id)->with('DoctorsSubject','DoctorService')->get();


        return response()->json([
           'status' => true,
           'data' => $get
        ],200);
    }


    /**
     * @OA\Post(
     * path="/api/doctor_visit_time",
     * summary="doctor_visit_time",
     * description="Получаем Список свободных часов Одного доктора для конкретной даты",
     * operationId="doctor_visit_time",
     * tags={"Doctors"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="subject_id", type="string", format="text", example="1"),
     *       @OA\Property(property="PL_EXAM_ID", type="string", format="text", example="1"),
     *       @OA\Property(property="start_date", type="string", format="text", example="2023-05-18"),
     *       @OA\Property(property="end_date", type="string", format="text", example="2023-05-18"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Visit Time Data",
     *    @OA\JsonContent(
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong Data sended subject_id and PL_EXAM_ID no exist",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */



    public function doctor_visit_time(Request $request){
        $rules=array(
            'subject_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'PL_EXAM_ID' => 'required'
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }

        $get_doctor_service = DoctorService::where('subject_id', $request->subject_id)->where('PL_EXAM_ID', $request->PL_EXAM_ID)->first();

        if ($get_doctor_service == null){
            return response()->json([
               'status' => false,
               'message' => 'Wrong Data',
            ],422);
        }

        $doctor_visit_time = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic ".env('token'),
        ])->timeout(5000000)->post(env('PAM_URL').'LK_GET_PLAN_TIME_EXAM_INTERVAL',
            [
                "pl_subj_id" => $request->subject_id,
                "date_start" => $request->start_date,
                "date_end" => $request->end_date,
                "pl_exam_id" => $request->PL_EXAM_ID
            ])->json();



        return response()->json([
           'status' => true,
           'data' =>  $doctor_visit_time['result']
        ],200);


    }
}
