<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\MyNaznachenia;
use Illuminate\Support\Facades\Validator;

class NaznacheniaController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/get_my_naznachenia",
     *     summary="Get My Naznachenia",
     *     tags={"Naznachenia"},
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

    public function get_my_naznachenia(){
        $client_token = env('token');
        $get_my_naznachenia = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $client_token",
        ])->timeout(5000000)->post(env('PAM_URL').'VIEW_LK_PATIENTS_DRUGS',
            [
                "patients_id"=> auth()->user()->client_id
            ])->json();
            foreach ($get_my_naznachenia['result'] as $item){
                MyNaznachenia::updateOrCreate(
                    [
                            'user_id' => auth()->user()->id,
                           'PATIENTS_ID'  => auth()->user()->client_id,
                           'PATDIREC_ID'  => $item['PATDIREC_ID'],
                    ],
                    [
                        'PATDIREC_ID'  => $item['PATDIREC_ID'],
                        'INTAKE_METHOD'  => $item['INTAKE_METHOD']??null,
                        'PATDIREC_DRUGS_ID'  => $item['PATDIREC_DRUGS_ID']??null,
                        'MEDICAMENT'  => $item['MEDICAMENT']??null,
                        'COUNT_PER_DAY'  => $item['COUNT_PER_DAY']??null,
                        'DOSE'  => $item['DOSE']??null,
                        'time'  => $item['time']??null,
                        'CREATE_DATE_TIME'  => $item['CREATE_DATE_TIME']??null,
                        'DESCRIPTION'  => $item['DESCRIPTION']??null,
                        'BEGIN_DATE_TIME'  => $item['BEGIN_DATE_TIME']??null,
                        'END_DATE_TIME'  => $item['END_DATE_TIME']??null,
                        'PLANE_DATE'  => $item['PLANE_DATE']??null,
                        'status' => 'PAM'
                    ]
                );
            }
            $get = MyNaznachenia::where('user_id', auth()->user()->id)->where('PLANE_DATE', '>', Carbon::now())->where('status','PAM')->get();
            return response()->json([
               'status' => true,
               'data' =>$get
            ],200);
    }


    /**
     * @OA\Post(
     *     path="/api/user_add_naznachenia",
     *     summary="Add Naznachenia",
     *     tags={"Naznachenia"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nazvaniya_lekarstva", type="string"),
     *             @OA\Property(property="vremya_priyoma_preporata", type="string"),
     *             @OA\Property(property="data_nachala", type="string"),
     *             @OA\Property(property="data_zaversheniya", type="string"),
     *             @OA\Property(property="dozirovka", type="string"),
     *             @OA\Property(property="pereodichnosty", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */

    public function user_add_naznachenia(Request $request){
        $rules=array(
            'nazvaniya_lekarstva' => 'required',
            'vremya_priyoma_preporata' => 'required',
            'data_nachala' => 'required',
            'data_zaversheniya' => 'required',
            'dozirovka' => 'required',
            'pereodichnosty' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }


        $end = explode('T',$request->data_zaversheniya );
        $start = explode('T',$request->data_nachala );

        MyNaznachenia::create([
               'status' => 'user',
               'user_id' => auth()->user()->id,
              'PATIENTS_ID' => auth()->user()->client_id,
              'MEDICAMENT' =>$request->nazvaniya_lekarstva,
              'DOSE' =>$request->dozirovka,
              'BEGIN_DATE_TIME' =>$start[0],
              'END_DATE_TIME' =>$end[0],
              'COUNT_PER_DAY' =>$request->vremya_priyoma_preporata,
              'pereodichnosty' => $request->pereodichnosty
        ]);

        return response()->json([
           'status' => true,
           'message' => 'naznachenia added'
        ],200);
    }

    /**
     * @OA\Get(
     *     path="/api/get_user_adds_naznachenia",
     *     summary="Get User Adds Naznachenia",
     *     tags={"Naznachenia"},
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

    public function get_user_adds_naznachenia(Request $request){

        $get = MyNaznachenia::where('user_id', auth()->user()->id)->where('status', 'user')->where('END_DATE_TIME','>=', Carbon::now()->format('Y-m-d'))->where('BEGIN_DATE_TIME', '<=', Carbon::now()->format('Y-m-d'))->get();


        return response()->json([
           'status' => true,
           'data' => $get
        ],200);
    }

    /**
     * @OA\Post(
     *     path="/api/delete_naznacheniya",
     *     summary="DELETE Naznachenia",
     *     tags={"Naznachenia"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="naznachenya_id", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function delete_naznacheniya(Request $request){
        $get = MyNaznachenia::where('id', $request->naznachenya_id)->first();
        if ($get != null ){
            if ($get->status != 'user'){
                return response()->json([
                   'status' => false,
                    'message' => 'You Don Have Permision from delete naznacheniya'
                ],422);
            }
            $get->delete();
            return response()->json([
               'status' => true,
               'message' => 'deleted'
            ],200);
        }


        return response()->json([
           'status' => false,
           'message' => 'Wrong naznacheniya ID'
        ],422);


    }



}
