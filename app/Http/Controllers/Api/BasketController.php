<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Basket;

class BasketController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/get_basket_status_green_or_red",
     *     summary="Get the count of baskets with a status of 1 (green or red) for the authenticated user",
     *     tags={"Basket"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="count", type="integer", example=5),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     * )
     */

    public function get_basket_status_green_or_red(){
        $get = Basket::where('user_id', auth()->user()->id)->where('status', 1)->count();


        return response()->json([
           'status' =>  true,
            'count' => $get
        ],200);
    }

    /**
     * @OA\Post(
     * path="/api/add_service_in_basket",
     * summary="add_service_in_basket",
     * description="Отправлаем услугу в корзину  ОТПРАВЛАЕМ TOKEN",
     * operationId="add_service_in_basket",
     * tags={"Basket"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="parent_type", type="string", format="text", example="doctor_service OR ... medical_test ... medical_test_parameter ... obs ... obs_service ... home_services"),
     *       @OA\Property(property="PL_EXAM_ID", type="string", format="text", example="if doctor_service  send PL_EXAM_ID"),
     *       @OA\Property(property="parent_id", type="string", format="text", example="data.doctor_service.id OR medical_test.id OR medical_test_parameter.id"),
     *       @OA\Property(property="start_time", type="string", format="text", example="t1"),
     *       @OA\Property(property="end_time", type="string", format="text", example="t2"),
     *       @OA\Property(property="day", type="string", format="text", example="day"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Added in Basket",
     *    @OA\JsonContent(
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="You have this service in basket",
     *    @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function add_service_in_basket(Request $request){
        $rules=array(
            'parent_type' => 'required',
            'parent_id' => 'required',
//            'day' => 'date',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }

        if ($request->parent_type == 'doctor_service'){
            $parent_type = 'App\Models\DoctorService';
        }
        if ($request->parent_type == 'medical_test'){
            $parent_type = 'App\Models\MedicalTest';
        }

        if ($request->parent_type == 'medical_test_parameter'){
            $parent_type = 'App\Models\MedicalTestParametr';
            $exam_id =  \App\Models\MedicalTestParametr::where('id', $request->parent_id)->first();
            $PL_EXAM_ID = \App\Models\MedicalTest::where('id', $exam_id->test_id)->first();
        }

        if ($request->parent_type == 'obs'){
            $parent_type = 'App\Models\ObsClinic';
        }
        if ($request->parent_type == 'obs_service'){
            $parent_type = 'App\Models\ObsClinicService';
        }

        if($request->parent_type == "home_services"){
            $parent_type = 'APP\Models\HomeService';
        }

        $get = Basket::where('user_id', auth()->user()->id)->where('parent_type', $parent_type)->where('parent_id', $request->parent_id)->where('status',1)->first();

        if($get != null){
            return response()->json([
               'status' => false,
               'message' => 'You have this service in basket',
               'basket_id' => $get->id
            ],422);
        }

       $create =  Basket::create([
            'user_id' => auth()->user()->id,
            'parent_type' => $parent_type,
            'parent_id' =>  $request->parent_id,
            'PL_EXAM_ID' => $PL_EXAM_ID->PL_EXAM_ID??$request->PL_EXAM_ID,
            'client_id' => auth()->user()->client_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'day' => $request->day
        ]);
        return response()->json([
           'status' => true,
           'message' => 'Added in Basket',
           'basket_id' => $create->id
        ],200);
    }



    /**
     * @OA\Get(
     * path="/api/get_basket",
     * summary="get_basket",
     * description="Получаем список моей корзини если нужен один экзепляр отправлаем  /api/get_basket/2 ОТПРАВЛАЕМ TOKEN",
     * operationId="get_basket",
     * tags={"Basket"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Basket data",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */


    public function get_basket($id = null){

            $basket = Basket::where('user_id', auth()->guard('api')->user()->id)->orderbY('id', 'desc')->with('parent');

            if ($id == null){
                $get = $basket->where('status', 1)->simplepaginate(10);
            }else{
                $get = $basket->where('id', $id)->get();
            }


            $online_pay = 'no required';

            $bassket_all_price = 0;


            foreach ($get as $price){
                $bassket_all_price += $price['parent']['PRICE']??$price['parent']['price']??$price['parent']['priceAmount'];

            }

            return response()->json([
               'status' => true,
               'online_pay' => $online_pay,
               'count' => $get->count(),
               'bassket_all_price' => $bassket_all_price,
               'data' => $get
            ],200);
        }

    /**
     * @OA\Get(
     * path="/api/my_basket_record",
     * summary="my_basket_record",
     * description="Получение списка записей поциента",
     * operationId="my_basket_record",
     * tags={"Basket"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Получение списка записей поциента",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
        public function my_basket_record(){

        $user_id = auth()->guard('api')->user()->id;

        $basket = Basket::where('user_id', $user_id)
            ->where(function ($query){
                $query->where('parent_type','App\Models\DoctorService')
                    ->orWhere('parent_type','App\Models\HomeServise');
            })
            ->with('parent')
            ->wherein('status', [3,5])
            ->get();
            return response()->json([
                'status' => true,
                'data' => $basket,
            ],200);

        }


    /**
     * @OA\Get(
     * path="/api/my_basket_record_limit3",
     * summary="my_basket_record_limit3",
     * description="Получение списка записей поциента лимит 3",
     * operationId="my_basket_record_limit3",
     * tags={"Basket"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Получение списка записей поциента лимит 3",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
    public function my_basket_record_limit3(){
        //return 123;
        $user_id = auth()->guard('api')->user()->id;


        $basket = Basket::where('user_id', $user_id)
            ->where(function ($query){
                $query->where('parent_type','App\Models\DoctorService')
                    ->orWhere('parent_type','App\Models\HomeServise');
            })
            ->wherein('status', [3,5])
            ->with('parent')
            ->orderBy('id','desc')
            ->limit(3)
            ->get();
        return response()->json([
            'status' => true,
            'data' => $basket,
        ],200);

    }

    /**
     * @OA\POST(
     *     path="/api/delete_my_all_basket",
     *     operationId="deleteMyAllBasket",
     *     tags={"Basket"},
     *     summary="Delete user's baskets",
     *     description="Delete all baskets belonging to the authenticated user send basket_id from delete single basket",
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
     *                 property="message",
     *                 type="string",
     *                 example="Basket Deleted"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */

        public function delete_my_all_basket(Request $request){
            if (isset($request->basket_id)){
                Basket::where('user_id', auth()->user()->id)->where('id' , $request->basket_id)->delete();
            }else{
                Basket::where('user_id', auth()->user()->id)->where('status' , 1)->delete();
            }


            return response()->json([
               'status' => true,
               'message' => 'Basket Deleted'
            ],200);
        }









}
