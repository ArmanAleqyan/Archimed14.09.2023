<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Basket;
use App\Models\Review;
use Carbon\Carbon;

class ReviewController extends Controller
{

    /**
     * @OA\Post(
     * path="/api/add_review",
     * summary="",
     * description="Add review to services by order",
     * operationId="Add_review_to_services_by_order",
     * tags={"OrderReview"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Add review to services by order",
     *    @OA\JsonContent(
     *       required={"doctor_services_id","description","grade","order_id","user_id"},
     *     @OA\Property(property="doctor_services_id", type="number", format="number", example="12"),
     *     @OA\Property(property="description", type="string", format="text", example="good"),
     *     @OA\Property(property="grade", type="number", format="number", example="5"),
     *     @OA\Property(property="order_id", type="number", format="number", example="12"),
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
    public function add_review(Request $request){
        //return $request->all();

        $doctor_services_id = $request->doctor_services_id;
        $user_id = auth()->guard('api')->user()->id;
        $order_id = $request->order_id;

        $order = Basket::find($order_id);

        //return $order;
        if(!$order){
            return response()->json([
                'status'=>false,
                'message'=>'you have not this order'
            ],402);
        }
        if($order->order_review == "true"){
            return response()->json([
                'status'=>false,
                'message'=>'you have already set review this order'
            ],402);
        }
        $order->order_review = 'true';
        $order->save();



        $order_date = $order->created_at;

        $carbonCreatedAt = Carbon::parse($order_date);
        $daysSinceCreation = $carbonCreatedAt->diffInDays(Carbon::now());


        if($daysSinceCreation > 14){
            return response()->json([
               'status'=>false,
               'message'=>'you placed an order earlier than 2 weeks'
            ],401);
        } else {
            $reviews = Review::where('doctor_services_id',$doctor_services_id)
                            ->where('user_id',$user_id)
                            ->get();
            if(count($reviews)>2){
                return response()->json([
                    'status'=>false,
                    'message'=>'you have already set 3 reviews this services'
                ],202);
            };

            //return $order_id;
            $result = Review::create([
                'doctor_services_id'=>$doctor_services_id,
                'user_id'=>$user_id,
                'description'=>$request->description,
                'grade'=>$request->grade,
                'order_id'=>$order_id,
            ]);
        }

        return response()->json([
            'status'=>true,
            'message'=>'review added'
        ],200);
    }
}
