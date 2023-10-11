<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PamNotification;
use App\Models\Basket;
use App\Models\MyNaznachenia;
use App\Models\UserDirection;
use App\Models\OtvetNaAnaliz;
class NotifyController extends Controller
{


    public function all_keys_count(){
        $get_notification = PamNotification::where('user_id',auth()->user()->id)->where('status', 1)->count();
        $all_orders_price = Basket::where('user_id',auth()->user()->id)->where('status', 3)->get();
        $bassket_all_price = 0;
        $get_pam_naznachenia = MyNaznachenia::where('user_id', auth()->user()->id)->where('PLANE_DATE', '>', Carbon::now())->where('status','PAM')->count();
        $get_my_naznachenia = MyNaznachenia::where('user_id', auth()->user()->id)->where('status', 'user')->where('END_DATE_TIME','>=', Carbon::now()->format('Y-m-d'))->count();
        $get_napravlenia = UserDirection::where('PATIENTS_ID', auth()->user()->client_id)->count();
        $get_my_zapis = Basket::where('parent_type', 'App\Models\DoctorService')->wherein('status', [5,3])->count();
        $get_new_analize_count = OtvetNaAnaliz::where('user_id' , auth()->user()->id)->where('status', 0)->count();

            foreach ($all_orders_price as $price){
                $bassket_all_price += $price['parent']['PRICE']??$price['parent']['price']??$price['parent']['priceAmount'];
              }
        return response()->json([
           'status' => true,
            'notify_count' => $get_notification,
            'basket_order_price' => $bassket_all_price,
            'pam_naznachenia' => $get_pam_naznachenia,
            'my_naznachenia' => $get_my_naznachenia,
            'napravlenia' => $get_napravlenia,
            'my_zapis' => $get_my_zapis,
            'get_new_analize_count' => $get_new_analize_count
        ],200);
    }


    /**
     * @OA\Get(
     * path="/api/get_my_notyfy",
     * summary="get_my_notyfy",
     * description="Получение уведомлений",
     * operationId="get_my_notyfy",
     * tags={"Notification"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Получение уведомлений",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
    public function get_my_notyfy(){
    $user_id = auth()->user()->id;

    $get_notification = PamNotification::where('user_id',$user_id)->orderby('id', 'desc')->simplePaginate(10);

//    dd($get_notification->pluck('id')->toarray());
//    if($get_notification['data']){
        PamNotification::wherein('id',$get_notification->pluck('id')->toarray())->orderby('id', 'desc')->limit(10)->update(['status'=>2]);
//    }

        
    return response()->json([
        'status' => true,
        'data' => $get_notification,
    ],200);
}

    /**
     * @OA\Get(
     * path="/api/has_mynotification",
     * summary="has_mynotification",
     * description="Проверка наличия новых уведомлений",
     * operationId="has_mynotification",
     * tags={"Notification"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Проверка наличия новых уведомлений",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
    public function has_mynotification(){
        $user_id = auth()->user()->id;

        $result = PamNotification::where('user_id',$user_id)->where('status',1)->first();


        if($result != null){
            return response()->json([
                'status'=>true,
                'message'=>'You have a new notification'
            ],200);
        } else {
            return response()->json([
                'status'=>false,
                'message'=>'have not new notification'
            ],200);
        }
}



}