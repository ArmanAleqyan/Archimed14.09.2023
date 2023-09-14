<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ObsClinic;
use App\Models\ObsClinicService;
use Illuminate\Support\Facades\Validator;

class ObsController extends Controller




    /**
     * @OA\Schema(
     *     schema="ObsClinic",
     *     title="Observation Clinic",
     *     description="Schema for Observation Clinic",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="NAME", type="string", example="OBS Name"),
     * )
     */


{

    /**
     * @OA\POST(
     *     path="/api/get_obs",
     *     tags={"Observation"},
     *     summary="Get observations",
     *     operationId="getObs",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search value",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ObsClinic"))
     *         )
     *     ),
     * )
     */

    public function get_obs(Request $request){
        $get = ObsClinic::query();
        $search_value = explode(" ", $request->search);

            if (isset($request->search)){
                foreach ($search_value as $doctor) {
                    $get->orWhere(function ($query) use ($doctor) {
                        $query
                            ->where('NAME', 'like', "%{$doctor}%");
                    });
                }
            }
        $gets = $get->orderBy('NAME', 'ASC')->get();

        return response()->json([
           'status' => true,
           'data' => $gets
        ],200);
    }

    /**
     * @OA\POST(
     *     path="/api/get_obs_service",
     *     tags={"Observation"},
     *     summary="Get observation services",
     *     operationId="getObsService",
     *     @OA\Parameter(
     *         name="obs_id",
     *         in="query",
     *         description="Observation ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search value",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *         )
     *     ),
     * )
     */



    public function get_obs_service(Request $request){
        $rules=array(
            'obs_id' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }
        $get_obs = ObsClinic::where('id', $request->obs_id)->first();

        if ($get_obs == null){
            return response()->json([
               'status' => false,
               'message' => 'wrong obs_id'
            ],422);
        }


      $get = ObsClinicService::query();
        $search_value = explode(" ", $request->search);

        if (isset($request->search)){
            foreach ($search_value as $doctor) {
                $get->orWhere(function ($query) use ($doctor) {
                    $query
                        ->where('CODE', 'like', "%{$doctor}%")
                        ->orwhere('LABEL', 'like', "%{$doctor}%")
                        ->orwhere('SHORT_LABEL', 'like', "%{$doctor}%")
                        ->orwhere('DESCRIPTION', 'like', "%{$doctor}%")
                    ;
                });
            }
        }

        $get =   $get->where('obs_id', $request->obs_id)->get();


        return response()->json([
           'status' => true,
           'data' => $get,
            'obs' => $get_obs
        ],200);
    }


}
