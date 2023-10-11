<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\UserDirection as UserDirectionModel;
class UserDirection extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/my_direction",
     *     operationId="myDirection",
     *     tags={"Direction"},
     *     summary="Get user directions",
     *     description="Retrieve user directions based on search criteria",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="search",
     *                 type="string",
     *                 description="Search term"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="direction data")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */


    public function my_direction(Request $request){
        $client_token = env('token');
        $get_my_direction = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $client_token",
        ])->timeout(5000000)->post(env('PAM_URL').'VIEW_LK_GET_PATIENTS_PATDIR',
            [
                "patients_id"=> auth()->user()->client_id
            ])->json();




        $get_my_table_direction = UserDirectionModel::where('PATIENTS_ID', auth()->user()->client_id)->count();
                if (count($get_my_direction['result'] ) != $get_my_table_direction){
                    foreach ($get_my_direction['result']  as $item) {
                        UserDirectionModel::updateOrCreate([
                            'PATDIREC_ID' => $item['PATDIREC_ID'],
                            'DATE_PATDIR' => $item['DATE_PATDIR'],
                            'PATIENTS_ID' => $item['PATIENTS_ID'],

                        ],[

                            'PATDIREC_ID' => $item['PATDIREC_ID']??null,
                            'DATE_PATDIR' => $item['DATE_PATDIR']??null,
                            'NAME' => $item['NAME']??null,
                            'DESCR_PATDIR' => $item['DESCR_PATDIR']??null,
                            'FIO_MED' => $item['FIO_MED']??null,
                            'CLINIC' => $item['CLINIC']??null,
                            'PATIENTS_ID' => $item['PATIENTS_ID']??null,
                            'STATE_PATDIR' => $item['STATE_PATDIR']??null,
                            'DATE_ACTUAL' => $item['DATE_ACTUAL']??null,
                        ]);
                    }
                }
                    $get = UserDirectionModel::query();
                    if (isset($request->search)){
                        $name_parts = explode(' ', $request->search);
                        foreach ($name_parts as $part){
                            $get->orWhere(function ($query) use ($part) {
                                $query
                                    ->where('NAME', 'like', "%{$part}%")
                                    ->orwhere('DESCR_PATDIR', 'like', "%{$part}%")
                                    ->orwhere('CLINIC', 'like', "%{$part}%")
                                    ->orwhere('DATE_PATDIR', 'like', "%{$part}%")
                                    ->orwhere('FIO_MED', 'like', "%{$part}%")
                                ;
                            });
                        }
                    }
              $gets =    $get->where('PATIENTS_ID', auth()->user()->client_id)
                  ->where('DATE_ACTUAL', '>=', Carbon::now())
                  ->simplepaginate(15);



                return response()->json([
                   'status' => true,
                   'data' => $gets
                ],200);
    }
}
