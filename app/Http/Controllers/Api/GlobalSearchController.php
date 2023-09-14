<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Basket;
use App\Models\HomeService;
use App\Models\MedicalTestParametr;
use App\Models\DoctorService;
use App\Models\DoctorList;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\DirectionsController ;
use Illuminate\Pagination\Paginator;

class GlobalSearchController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/get_global_search",
     * summary="get_global_search",
     * description="Глобальный поиск",
     * operationId="get_global_search",
     * tags={"GlobalSearch"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="search_type", type="string", format="text", example="all/news/basket/direction/naznacheniya/analise/homeServices/doctorService/doctor"),     *
     *       @OA\Property(property="searchText", type="string", format="text", example="Екатерина/крови"),     *
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Глобальный поиск",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */
    //name_exam, LABEL , NAME , FIO
    public function get_global_search(Request $request){

        $searchText = $request->searchText;
        $auth_user = auth()->guard('api')->user();
        $search_type = $request->search_type;

        if($search_type == "all"){
            //find news_sales start
            $get_news = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(5000000)->get('https://arhimed.clinic/api_news/get.php',
                [
                    'token' => 'c32D33eSDmf3de77eSa36d0Utd'
                ])->json();
            $filtr_news_sales = array_slice($get_news['result'], $request->offset,1);
            $filter_news_sales_search_finded = [];

            foreach ($filtr_news_sales as $val){
                $val_worlds = explode(" ",$val['NAME']);
                foreach ($val_worlds as $val_world){
                    if($val_world == $searchText){
                        array_push($filter_news_sales_search_finded,$val['NAME']);
                    }
                }
            }
            // find news_sales end

            // find Basket start
            $finded_basket = [];
            if($auth_user) {
                $baskets = Basket::with('parent')->where('parent_type', 'App\Models\DoctorService')->get();

                foreach ($baskets as $basket) {
                    $search_name = '';
                    if (isset($basket->parent->name_exam)) {
                        $search_name = $basket->parent->name_exam;
                    }
                    if ($search_name) {
                        $basket_wolds = explode(" ", $search_name);
                        foreach ($basket_wolds as $world) {
                            if ($world == $searchText) {
                                array_push($finded_basket,$basket->parent);
                            }
                        }
                    }
                }
            }
            // find Basket end
            

            // find direction start
            $find_deraction = [];
            if($auth_user){
                $user_id = auth()->guard('api')->user()->id;
                $patients_id =  Basket::select('client_id')->where('user_id',$user_id)->first();
                //return $patients_id->client_id;

                $direction = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Basic EsjUXa4FFU-PqDSmD7S5lw",
                ])->timeout(5000000)->post(env('PAM_URL').'VIEW_LK_GET_PATIENTS_PATDIR',
                    [
                        "patients_id"=>$patients_id->client_id,
                    ])->json();

                $find_deraction = [];

                foreach ($direction['result'] as $direct){
                    $direct_word = explode(" ",$direct['NAME']);
                    foreach ($direct_word as $val){
                        if($val == $searchText){
                            array_push($find_deraction,$direct);
                        }
                    }
                }
            }
            // find direction end

            //find naznacheniya start
            $find_naznacheniya = [];
            if($auth_user){
                $patients_id = auth()->guard('api')->user()->id;
                $naznacheniya = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Basic EsjUXa4FFU-PqDSmD7S5lw",
                ])->timeout(5000000)->post(env('PAM_URL').'VIEW_LK_GET_PATIENTS_PATDIR',
                    [
                        "patients_id"=>$patients_id,
                    ])->json();

                $find_naznacheniya = [];
                foreach ($naznacheniya['result'] as $nasnach){
                    $nasnach_word = explode(' ',$nasnach['NAME']);
                    foreach ($nasnach_word as $val){
                        if($val == $searchText){
                            array_push($find_naznacheniya,$nasnach);
                        }
                    }
                }
            }
            //find naznacheniya end

            //find analises start
            $search_value = explode(" ", $searchText);

            $medical_test = MedicalTestParametr::where('LABEL','like','%'.$searchText.'%')->simplePaginate();
            //find analises end

            //find home_services start
            $home_services = HomeService::get();
            //return $home_services;
            $find_home_services = [];
            foreach ($home_services as $service){
                $service_word = explode(" ",$service->LABEL);
                foreach ($service_word as $word){
                    if($word == $searchText){
                        array_push($find_home_services,$service);
                    }
                }
            }
            //find home_services end

            //find doctor_services start
            $doctor_services = DoctorService::where('name_exam','like','%'.$searchText.'%')->simplePaginate(10);
            //find doctor_services end

            //find doctor_list start
            $doctor_list = DoctorList::where('FIO','like','%'.$searchText.'%')->simplePaginate(10);
            //find doctor_list end

            return response()->json([
                'status'=>true,
                'data'=>[
                    'news_sales' => $filter_news_sales_search_finded,
                    'My_notes' => $finded_basket,
                    'Direction'=> $find_deraction,
                    'Naznacheniya'=>$find_naznacheniya,
                    'Analises' => $medical_test,
                    'Home_services' => $find_home_services,
                    'Doctor_services' => $doctor_services,
                    'Doctor_list' => $doctor_list,
                ]
            ],200);
        } else if($search_type == "news"){
            $get_news = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(5000000)->get('https://arhimed.clinic/api_news/get.php',
                [
                    'token' => 'c32D33eSDmf3de77eSa36d0Utd'
                ])->json();
            $filtr_news_sales = array_slice($get_news['result'], $request->offset,1);
            $filter_news_sales_search_finded = [];

            foreach ($filtr_news_sales as $val){
                $val_worlds = explode(" ",$val['NAME']);
                foreach ($val_worlds as $val_world){
                    if($val_world == $searchText){
                        array_push($filter_news_sales_search_finded,$val['NAME']);
                    }
                }
            }

            return response()->json([
                'status'=>true,
                'data'=>[
                    'news_sales' => $filter_news_sales_search_finded,
                ]
            ],200);
        } else if($search_type == "basket"){
            $finded_basket = [];
            if($auth_user) {
                $baskets = Basket::with('parent')->where('parent_type', 'App\Models\DoctorService')->get();

                foreach ($baskets as $basket) {
                    $search_name = '';
                    if (isset($basket->parent->name_exam)) {
                        $search_name = $basket->parent->name_exam;
                    }
                    if ($search_name) {
                        $basket_wolds = explode(" ", $search_name);
                        foreach ($basket_wolds as $world) {
                            if ($world == $searchText) {
                                array_push($finded_basket,$basket->parent);
                            }
                        }
                    }
                }
            }
            return response()->json([
                'status'=>true,
                'data'=>[
                    'My_notes' => $finded_basket,
                ]
            ],200);
        }else if($search_type == "direction"){
            $find_deraction = [];
            if($auth_user){
                $user_id = auth()->guard('api')->user()->id;
                $patients_id =  Basket::select('client_id')->where('user_id',$user_id)->first();
                //return $patients_id->client_id;

                $direction = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Basic EsjUXa4FFU-PqDSmD7S5lw",
                ])->timeout(5000000)->post(env('PAM_URL').'VIEW_LK_GET_PATIENTS_PATDIR',
                    [
                        "patients_id"=>$patients_id->client_id,
                    ])->json();

                $find_deraction = [];

                foreach ($direction['result'] as $direct){
                    $direct_word = explode(" ",$direct['NAME']);
                    foreach ($direct_word as $val){
                        if($val == $searchText){
                            array_push($find_deraction,$direct);
                        }
                    }
                }
            }
            return response()->json([
                'status'=>true,
                'data'=>[
                    'Direction'=> $find_deraction,
                ]
            ],200);
        }else if($search_type == "naznacheniya"){
            $naznacheniya = [];
            if($auth_user){
                $patients_id = auth()->guard('api')->user()->id;
                $naznacheniya = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Basic EsjUXa4FFU-PqDSmD7S5lw",
                ])->timeout(5000000)->post(env('PAM_URL').'VIEW_LK_GET_PATIENTS_PATDIR',
                    [
                        "patients_id"=>$patients_id,
                    ])->json();

                $find_naznacheniya = [];
                foreach ($naznacheniya['result'] as $nasnach){
                    $nasnach_word = explode(' ',$nasnach['NAME']);
                    foreach ($nasnach_word as $val){
                        if($val == $searchText){
                            array_push($find_naznacheniya,$nasnach);
                        }
                    }
                }
            }
            return response()->json([
                'status'=>true,
                'data'=>[
                    'Naznacheniya'=>$find_naznacheniya,
                ]
            ],200);
        }else if($search_type == "analise"){
            $search_value = explode(" ", $searchText);

            $medical_test = MedicalTestParametr::where('LABEL','like','%'.$searchText.'%')->simplePaginate();
            return response()->json([
                'status'=>true,
                'data'=>[
                    'Analises' => $medical_test,
                ]
            ],200);
        }else if($search_type == "homeServices"){
            $home_services = HomeService::get();
            //return $home_services;
            $find_home_services = [];
            foreach ($home_services as $service){
                $service_word = explode(" ",$service->LABEL);
                foreach ($service_word as $word){
                    if($word == $searchText){
                        array_push($find_home_services,$service);
                    }
                }
            }
            return response()->json([
                'status'=>true,
                'data'=>[
                    'Home_services' => $find_home_services,
                ]
            ],200);
        }else if($search_type == "doctorService"){
            $doctor_services = DoctorService::where('name_exam','like','%'.$searchText.'%')->simplePaginate();
            return response()->json([
                'status'=>true,
                'data'=>[
                    'Doctor_services' => $doctor_services,
                ]
            ],200);
        }else if($search_type == "doctor"){
            $doctor_list = DoctorList::where('FIO','like','%'.$searchText.'%')->simplePaginate();
            return response()->json([
                'status'=>true,
                'data'=>[
                    'Doctor_list' => $doctor_list,
                ]
            ],200);
        }





    }
}
