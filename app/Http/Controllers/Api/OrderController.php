<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Basket;
use Illuminate\Support\Facades\Http;
use App\Models\DoctorSubject;
use Illuminate\Support\Facades\Validator;
use App\Models\HomeService;

class OrderController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/add_new_order",
     *     summary="Add a new order (online or offline)",
     *     tags={"Orders"},
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Response(response="422", description="Unprocessable entity"),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pay_type"},
     *             @OA\Property(
     *                 property="pay_type",
     *                 description="Payment type (online or offline)",
     *                 type="string",
     *                 enum={"online Or offline"}
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function add_new_order(Request $request){

        $rules=array(
            'pay_type' => 'required|in:ONLINE,OFFLINE',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }
      
        $doctor_data_from_create =[];
        $service_data_from_create =[];

        $get_basket = Basket::where('user_id', auth()->user()->id)->where('status',1)->with('basketable')->get();


          if ($get_basket ->isEMpty()){
              return response()->json([
                 'status' => false,
                 'message' => 'I not have uslugs in basket'
              ],422);
          }

        $price = 0;
        $time = time();
          foreach ($get_basket  as $basket){

              if ($basket['parent_type'] == 'App\Models\DoctorService'){
                  $get = \App\Models\DoctorService::where('id', $basket->parent_id)->first();
                  $get_doctor_subject = DoctorSubject::where('doctor_id', $get->doctor_id)->first();

                  $doctor_visit_time = Http::withHeaders([
                      'Content-Type' => 'application/json',
                      'Authorization' => "Basic ".env('token'),
                  ])->timeout(5000000)->post(env('PAM_URL').'LK_CREATE_EXCL',
                      [
                          "pl_subj_id" => $get_doctor_subject->PL_SUBJ_ID,
                          "date_start" => $basket->day.'T'.$basket->start_time,
                          "date_end" => $basket->day.'T'.$basket->end_time,
                      ])->json();


                  if (isset($doctor_visit_time['result'][0]['error']) && $doctor_visit_time['result'][0]['error'] == 'Ошибка создания исключительного события'){
                      $doctor_visit_time2 = Http::withHeaders([
                          'Content-Type' => 'application/json',
                          'Authorization' => "Basic ".env('token'),
                      ])->timeout(5000000)->post(env('PAM_URL').'LK_GET_PLAN_TIME_EXAM_INTERVAL',
                          [
                              "pl_subj_id" => $get_doctor_subject->PL_SUBJ_ID,
                              "date_start" => $basket->day,
                              "date_end" => $basket->day,
                              "pl_exam_id" => $get->PL_EXAM_ID
                          ])->json();

                      return response()->json([
                          'status' => false,
                          'message' => 'This slot no active',
                          'next_url' => 'update_basket_device',
                          'basket_id' =>  $basket->id,
                          'visit_time' => $doctor_visit_time2['result']??null
                      ],422);
                  }else{
                      $basket->update([
                          'id_excl' => $doctor_visit_time['result'][0]['id_excl']??null,
                          'status' => 2,
                          'order_id' =>$time,
                          'pay_type' => $request->pay_type
                      ]);
                  }

                  $get_doctor_service = \App\Models\DoctorService::where('PL_EXAM_ID',$basket->PL_EXAM_ID)->first();
                  $get_doctor_id =\App\Models\DoctorList::where('id', $get_doctor_service->doctor_id)->first();
                  $doctor_data_from_create[] = [
                      'id_doctor' => $get_doctor_id->MEDECINS_ID,
                      'type' => $basket->PL_EXAM_ID,
                      'date' => $basket->day,
                      'time' => $basket->start_time,
                      'PL_EXAM_ID' => $basket->PL_EXAM_ID,
                      "name_service" => $get_doctor_service->name_exam
                  ];
              }
              //home services order
              if($basket['parent_type'] == 'App\Models\HomeService'){

                  $home_service = HomeService::find($basket->parent_id);
                  $serv_code = $home_service->CODE;
                    $start_time = $basket['start_time']??"";
                    $basket_day = $basket['day']??"";
                    $data_start = $basket_day." ".$start_time;

                  $home_services_visit_first_order = Http::withHeaders([
                      'Content-Type' => 'application/json',
                      'Authorization' => "Basic ".env('token'),
                  ])->timeout(5000000)->post(env('PAM_URL').'LK_CREATE_EXCL_VIEZD',
                      [
                          "date_start" => $data_start,
                          "serv_code" => $serv_code,
                      ])->json();
              }
 //             home services order end

              $basket->update([
                 'status' => 2,
                 'order_id' => $time,
                  'pay_type' => $request->pay_type
              ]);

          $price += $basket['parent']['PRICE']??$basket['parent']['price']??$basket['parent']['priceAmount'];

            $get_from_create = $basket->parent_type::where('id',$basket->parent_id)->first();

            if ($basket['parent_type'] != 'App\Models\DoctorService'){
                $service_data_from_create[] =[
                    'type_service' => 'PATDIREC',
                    'id_service' => $get_from_create->CODE,
                    'name_service' => $get_from_create->LABEL,
                    'cost_service' => $get_from_create->price,
                    'PL_EXAM_ID' => $basket->PL_EXAM_ID,
                ];
            };

              if ($doctor_data_from_create != []){
                  $data = [
                      'Order' => [
                          'filial_id' => auth()->user()->filial_id??1,
                          'patients_id' => auth()->user()->client_id,
                          'payment' => $request->pay_type,
                          'services' => [
                              $service_data_from_create
                          ],
                          'doctors' =>
                              $doctor_data_from_create
                      ]
                  ];
              }else{
                  $data = [
                      'Order' => [
                          'filial_id' => auth()->user()->filial_id??1,
                          'patients_id' => auth()->user()->client_id,
                          'payment' => $request->pay_type,
                          'services' =>
                              $service_data_from_create

                      ]
                  ];
              }
//              $basket->update([
//                  'status' => 2,
//                  'order_id' => $time,
//                  'pay_type' => $request->pay_type
//              ]);

          }

        $create_order  = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic ".env('token'),
        ])->timeout(5000000)->post(env('PAM_URL').'LK_CREATE_ORDER',
             $data
            );


        $response = json_decode($create_order->getBody()->getContents(), true);
        $order_id = $response['result'][0]['order_id'];



            Basket::where('order_id', $time)->update([
                'client_order_id' => $order_id
            ]);

          return response()->json([
             'status' => true,
             'price' => $price,
              'my_order_id' => $time,
              'client_order_id' => $order_id
          ],200);
     }

    /**
     * @OA\Post(
     *     path="/api/order_payment_successfully",
     *     tags={"Orders"},
     *     summary="Process successful order payment",
     *     operationId="orderPaymentSuccessfully",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="order_id", type="string", example="ABC123"),
     *             @OA\Property(property="basket_id", type="string", example="DEF456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order Created")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or wrong order_id/basket_id",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error or wrong order_id/basket_id")
     *         )
     *     )
     * )
     */
     public function order_payment_successfully(Request $request){
         $rules=array(
             'order_id' => 'required',

         );
         $validator=Validator::make($request->all(),$rules);
         if($validator->fails())
         {
             return $validator->errors();
         }
         if (isset($request->basket_id)){
             $get = Basket::where('order_id', $request->order_id)->where('id', $request->basket_id)->first();
             if ($get == null){
                 return response()->json([
                    'status'  => false,
                     'message' => 'wrong order_id or basket_id'
                 ],422);
             }
             if ($get ->parent_type == 'App\Models\DoctorService'){
                 $get_doctor_service = \App\Models\DoctorService::where('id', $get->parent_id)->first();
                 $doctor_brone = Http::withHeaders([
                     'Content-Type' => 'application/json',
                     'Authorization' => "Basic ".env('token'),
                 ])->timeout(5000000)->post(env('PAM_URL').'LK_CREATE_PLANNING',
                     [
                         "pl_subj_id" => $get_doctor_service->subject_id,
                         "date_start" => $get->day.'T'.$get->start_time,
                         "date_end" => $get->day.'T'.$get->end_time,
                         "pl_exam_id" => $get->PL_EXAM_ID,
                         'patients_id' => auth()->user()->client_id
                     ])->json();

                 if (isset($doctor_brone['result'][0]['planning_id'])){
                     $get->update([
                         'planning_id' =>$doctor_brone['result'][0]['planning_id']
                     ]);
                 }else{
                     return response()->json([
                        'status' => false,
                        'message' =>  $doctor_brone['result'][0]['error']
                     ],422);
                 }
             }
             //Home service start
             if($get ->parent_type == 'App\Models\HomeService'){
                 $home_service = HomeService::find($get->parent_id);
                 $serv_code = $home_service->CODE;
                 $start_time = $get->start_time??"";
                 $basket_day = $get->day??"";
                 $data_start = $basket_day." ".$start_time;
                 //return $serv_code;

                 $doctor_brone = Http::withHeaders([
                     'Content-Type' => 'application/json',
                     'Authorization' => "Basic ".env('token'),
                 ])->timeout(5000000)->post(env('PAM_URL').'LK_CREATE_PLANNING_VIEZD',
                     [
                         "date_start"=>$data_start,
                         "patients_id"=>$get->client_id,
                         "dopinfo"=>"description",
                         "serv_code"=>$serv_code
                     ])->json();
             }
             //home service end
             $get->update([
                'status' => 3
             ]);
             return response()->json([
                 'status' => true,
                 'message' => 'Order Created'
             ]);
         }else{
             $gets = Basket::where('order_id', $request->order_id)->get();
             if ($gets->isEMpty()){
                 return response()->json([
                     'status'  => false,
                     'message' => 'wrong order_id'
                 ],422);
             }else{
                foreach ($gets as $doctor_service){
                    if ($doctor_service ->parent_type == 'App\Models\DoctorService'){
                        $get_doctor_service = \App\Models\DoctorService::where('id', $doctor_service->parent_id)->first();
                        $doctor_brone = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Authorization' => "Basic ".env('token'),
                        ])->timeout(5000000)->post(env('PAM_URL').'LK_CREATE_PLANNING',
                            [
                                "pl_subj_id" => $get_doctor_service->subject_id,
                                "date_start" => $doctor_service->day.'T'.$doctor_service->start_time,
                                "date_end" => $doctor_service->day.'T'.$doctor_service->end_time,
                                "pl_exam_id" => $doctor_service->PL_EXAM_ID,
                                'patients_id' => auth()->user()->client_id
                            ])->json();

                        if (isset($doctor_brone['result'][0]['planning_id'])){
                            $doctor_service->update([
                                'planning_id' =>$doctor_brone['result'][0]['planning_id']
                            ]);
                        }else{
                            return response()->json([
                                'status' => false,
                                'message' =>  $doctor_brone['result'][0]['error']
                            ],422);
                        }
                    }
                    if($doctor_service ->parent_type == 'App\Models\HomeService'){

                        $home_service = HomeService::find($doctor_service->parent_id);
                        $serv_code = $home_service->CODE;
                        $start_time = $doctor_service->start_time??"";
                        $basket_day = $doctor_service->day??"";
                        $data_start = $basket_day." ".$start_time;
                        //return $doctor_service->client_id;

                        $doctor_brone = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Authorization' => "Basic ".env('token'),
                        ])->timeout(5000000)->post(env('PAM_URL').'LK_CREATE_PLANNING_VIEZD',
                            [
                                "date_start"=>$data_start,
                                "patients_id"=>$doctor_service->client_id,
                                "dopinfo"=>"description",
                                "serv_code"=>$serv_code
                            ])->json();
                    }
                }
                 Basket::where('order_id', $request->order_id)->update([
                    'status' => 3
                 ]);
                 return response()->json([
                     'status' => true,
                        'message' => 'Order Created'
                 ]);
             }
         }
     }

    /**
     * @OA\Post(
     *     path="/api/update_basket_device",
     *     summary="Update basket device",
     *     tags={"Basket"},
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Response(response="422", description="Unprocessable entity"),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref=" ")
     *     ),
     *     @OA\Parameter(
     *         name="basket_id",
     *         in="query",
     *         description="Basket ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="day",
     *         in="query",
     *         description="Day",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="start_day",
     *         in="query",
     *         description="Start day",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="end_day",
     *         in="query",
     *         description="End day",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

     public function update_basket_device(Request $request){
         $rules=array(
             'basket_id' => 'required',
             'day' => 'required',
             'start_day' => 'required',
             'end_day' => 'required',
         );
         $validator=Validator::make($request->all(),$rules);
         if($validator->fails())
         {
             return $validator->errors();
         }
         $get = Basket::where('id', $request->basket_id)->first();
         if ($get == null){
             return response()->json([
                 'status' => false,
                'message' => 'wrong basket_id'
             ],422);
         }
         $get->update([
            'day' => $request->day,
            'start_day' => $request->start_day,
            'end_day' => $request->end_day
         ]);
         return response()->json([
            'status' => true,
            'message' => 'basket updated'
         ],200);
     }

    /**
     * @OA\POST(
     *     path="/api/get_my_all_orders",
     *     summary="Get all orders for the current user",
     *     tags={"Orders"},
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Response(response="422", description="Unprocessable entity"),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page for pagination",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

     public function get_my_all_orders(){
         $get = Basket::where('status', '!=',1)->where('user_id', auth()->user()->id)->with('parent')->simplepaginate(15);

         foreach ($get as $history){
             if ($history->pay_type == 'offline'){
                 $history->status = 'Оплата в клинике';

                 if ($history->status == 4){
                     $history->status = 'Пройдено';
                 }
             }else{
                 if ($history->status == 2){
                     $history->status = 'Не оплачено';
                 }
                 if ($history->status == 3){
                     $history->status = 'Оплачено';
                 }
                 if ($history->status == 4){
                     $history->status = 'Пройдено';
                 }
             }
         }


         return response()->json([
            'status' => true,
            'data' => $get
         ],200);
     }



     public function cancel_order(Request $request){
         $rules=array(
             'order_id' => 'required',
         );
         $validator=Validator::make($request->all(),$rules);
         if($validator->fails())
         {
             return $validator->errors();
         }


         $get_basket = Basket::where('order_id', $request->order_id)->get();

         foreach ($get_basket as $baskov){
             if ($baskov['pay_type'] == 'ONLINE'){
                 return response()->json([
                        'status' => false,
                     'message' => 'you cannot cancel order with online payment'
                 ],422);
             }
         }

         if ($get_basket->isEMpty()){
             return response()->json([
                'status' => false,
                 'message' => 'wrong order_id example 1685459076'
             ],422);
         }

         $cancel_order = Http::withHeaders([
             'Content-Type' => 'application/json',
             'Authorization' => "Basic ".env('token'),
         ])->timeout(5000000)->post(env('PAM_URL').'LK_CANCEL_ORDER',
             [
                 'patients_id' => auth()->user()->client_id,
                 'order_id' => $get_basket[0]->client_order_id
             ])->json();

         Basket::where('order_id', $request->order_id)->update([
             'status' => 1
         ]);

         return response()->json([
            'status' => 'Your All order deleted'
         ],422);

     }

    /**
     * @OA\Get(
     * path="/api/get_auth_user_order",
     * summary="get auth user orders",
     * description="get_auth_user_order",
     * operationId="get_auth_user_order",
     * tags={"Orders"},
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
     public function get_auth_user_order(){
         $user_id = auth()->user()->id;

         $order = Basket::where('user_id',$user_id)->where('status','>',1)->get();

         return response()->json([
             'status'=>true,
             'data'=>$order
         ]);

     }






}
