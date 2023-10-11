<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\StartInfoController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\LoginCountController;
use App\Http\Controllers\Api\NewsAndSalesController;
use App\Http\Controllers\Api\AppleRegisterAndLoginController;
use App\Http\Controllers\Cron\ImageDeletController;
use App\Http\Controllers\Application\HomePageController;
use App\Http\Controllers\Application\RefreshTokenController;
use App\Http\Controllers\Application\ClinicsController;
use App\Http\Controllers\Application\UpdateProfileController;
use App\Http\Controllers\Api\DoctorsController;
use App\Http\Controllers\Api\MedicalTestController;
use App\Http\Controllers\Api\BasketController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ObsController ;
use App\Http\Controllers\Api\OrderController ;
use App\Http\Controllers\Api\UserDirection ;
use App\Http\Controllers\Api\NaznacheniaController ;
use App\Http\Controllers\Api\NewsController ;
use App\Http\Controllers\Api\TestResultController ;
use App\Http\Controllers\Api\DepartureController ;
use App\Http\Controllers\Api\AboutAnaliseController ;
use App\Http\Controllers\Api\DirectionsController ;
use App\Http\Controllers\Api\ClinicController ;
use App\Http\Controllers\Application\NotifyController ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\FromPAM\NotificationController;

use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\AboutAppController;
use App\Http\Controllers\Api\AnaliseAndServices;
use App\Http\Controllers\Api\GlobalSearchController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('new_notification',[NotificationController::class,'new_notification']);

Route::post('test_add_order',[OrderController::class,'test_add_order']);

Route::get('StartInfo', [StartInfoController::class, 'StartInfo']);

Route::post('CreateAnalisePage', [SearchController::class, 'CreateAnalisePage']);
Route::post('CreateCategoryPage', [SearchController::class, 'CreateCategoryPage']);

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('get_news',[NewsController::class,'get_news']);

Route::post('LoginForPhone', [LoginController::class, 'LoginForPhone']);
Route::post('confirmPhoneLoginCode', [LoginController::class, 'confirmPhoneLoginCode']);
Route::post('Feedback', [LoginController::class,'Feedback']);


Route::post('register', [RegisterController::class, 'register']);
Route::post('validation_register', [RegisterController::class, 'validation_register']);
Route::post('confirmNewAccount', [RegisterController::class, 'confirmNewAccount']);
Route::post('requestNewAccountConfirmationCode', [RegisterController::class, 'requestNewAccountConfirmationCode']);
Route::post('login', [LoginController::class, 'login']);

Route::post('RepeatTheCall', [RegisterController::class,'RepeatTheCall']);
Route::post('PhoneCodeVerify', [RegisterController::class,'PhoneCodeVerify']);


Route::get('GetCity', [RegisterController::class, 'GetCity']);
Route::post('GeoAccess', [RegisterController::class, 'GeoAccess']);

Route::post('search', [SearchController::class, 'search']);

Route::post('LoginCount', [LoginCountController::class, 'LoginCount']);
Route::post('PersonalInformation', [RegisterController::class, 'PersonalInformation']);
Route::post('UserAddNewCity', [RegisterController::class,'UserAddNewCity']);


Route::get('NewsAndSales', [NewsAndSalesController::class, 'NewsAndSales']);
Route::get('InfoArchimedMedical', [NewsAndSalesController::class, 'InfoArchimedMedical']);


Route::post('RegisterFromApple',[AppleRegisterAndLoginController::class ,'RegisterFromApple']);
Route::post('LoginFromApple',[AppleRegisterAndLoginController::class ,'LoginFromApple']);


Route::post('doctors_list',[DoctorsController::class, 'doctors_list']);
Route::post('single_doctor',[DoctorsController::class, 'single_doctor']);

Route::post('medical_test',[MedicalTestController::class, 'medical_test']);
Route::post('medical_test_params',[MedicalTestController::class, 'medical_test_params']);
Route::post('medical_test_params_single_page',[MedicalTestController::class, 'medical_test_params_single_page']);

Route::post('get_obs', [ObsController::class,'get_obs']);
Route::post('get_obs_service', [ObsController::class,'get_obs_service']);


Route::group(['middleware' => ['auth:api']], function () {
    Route::post('Logout', [LoginController::class,'Logout']);
    Route::post('get_my_naznachenia',[NaznacheniaController::class,'get_my_naznachenia']);
    Route::post('user_add_naznachenia',[NaznacheniaController::class,'user_add_naznachenia']);
    Route::post('get_user_adds_naznachenia',[NaznacheniaController::class,'get_user_adds_naznachenia']);
    Route::post('delete_naznacheniya',[NaznacheniaController::class,'delete_naznacheniya']);

    Route::post('UserAddCity', [RegisterController::class, 'UserAddCity']);

//    Route::get('HomePageApp', [HomePageController::class, 'HomePageApp']);
//    Route::post('RefreshClientToken', [RefreshTokenController::class, 'RefreshClientToken']);
//    Route::post('VisitDoctorAddOrder', [ClinicsController::class,'VisitDoctorAddOrder']);
    Route::post('OrderPaymentType', [ClinicsController::class,'OrderPaymentType']);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Profile My Cabinet
    Route::get('getUserPage', [UpdateProfileController::class, 'getUserPage']);
    Route::post('UpdateUser', [UpdateProfileController::class, 'UpdateUser']);
    Route::get('getDocumentsType', [UpdateProfileController::class, 'getDocumentsType']);
    Route::post('UserAddNewDocument', [UpdateProfileController::class, 'UserAddNewDocument']);


/////////////////////////////////////        Basket
    Route::post('add_service_in_basket',[BasketController::class,'add_service_in_basket']);
    Route::get('get_basket/{id?}', [BasketController::class, 'get_basket']);
    Route::get('get_basket_status_green_or_red', [BasketController::class, 'get_basket_status_green_or_red']);
    Route::post('delete_my_all_basket', [BasketController::class, 'delete_my_all_basket']);
    Route::get('my_basket_record', [BasketController::class, 'my_basket_record']);
    Route::get('my_basket_record_limit3', [BasketController::class, 'my_basket_record_limit3']);

    Route::get('auth_user_info', [UserController::class, 'auth_user_info']);
    Route::post('update_user_info', [UserController::class,'update_user_info']);

    Route::post('add_new_order',[OrderController::class, 'add_new_order']);
    
    Route::post('update_basket_device',[OrderController::class, 'update_basket_device']);
    Route::post('get_my_all_orders',[OrderController::class, 'get_my_all_orders']);
    Route::post('order_payment_successfully',[OrderController::class, 'order_payment_successfully']);
    Route::post('cancel_order',[OrderController::class, 'cancel_order']);

    Route::get('get_auth_user_order',[OrderController::class,'get_auth_user_order']);

    ////////////////// Direction
    Route::post('my_direction',[UserDirection::class,'my_direction']);

    //Review
    Route::post('add_review',[ReviewController::class,'add_review']);

    //Departure
    Route::post('setDeparture',[DepartureController::class,'setDeparture']);

    //Home_services
    Route::get('get_home_service',[DepartureController::class,'get_home_service']);

    //Visov vracha
    Route::post('get_free_time_for_services',[DepartureController::class,'get_free_time_for_services']);

    //home services orders
    Route::post('add_first_order_to_home_services',[OrderController::class,'add_first_order_to_home_services']);

    //Test result
    Route::post('get_test_result',[TestResultController::class,'get_test_result']);
    Route::get('get_analis',[TestResultController::class,'get_analis']);
    Route::get('get_analis_limit3',[TestResultController::class,'get_analis_limit3']);
    Route::get('get_directions',[DirectionsController::class,'get_directions']);

    //My notifyController
    Route::get('get_my_notyfy',[NotifyController::class,'get_my_notyfy']);
    Route::get('all_keys_count',[NotifyController::class,'all_keys_count']);
    Route::get('has_mynotification',[NotifyController::class,'has_mynotification']);

});

//About aplication
Route::get('get_ApplicationUse',[AboutAppController::class,'get_ApplicationUse']);
Route::get('get_PrivacyPolicy',[AboutAppController::class,'get_PrivacyPolicy']);
Route::get('get_TermsOfService',[AboutAppController::class,'get_TermsOfService']);

//Question
Route::get('get_question',[QuestionController::class,'get_question']);

//Analise_and_service
Route::get('get_analise_and_service',[AnaliseAndServices::class,'get_analise_and_service']);

//Global search
Route::post('get_global_search',[GlobalSearchController::class,'get_global_search']);

//About analise info
Route::get('get_about_analise_info',[AboutAnaliseController::class,'get_about_analise_info']);

//Clinics
Route::get('get_clinics',[ClinicController::class,'get_clinics']);
Route::post('get_clinics_by_id',[ClinicController::class,'get_clinics_by_id']);
Route::get('get_Moscow_clinic_phone',[ClinicController::class,'get_Moscow_clinic_phone']);


Route::post('doctor_visit_time', [DoctorsController::class, 'doctor_visit_time']);

Route::get('newClinics', [ClinicsController::class, 'newClinics']);
Route::get('doctorsList', [ClinicsController::class, 'doctorsList']);
Route::get('SinglePageDoctor/doctor_id={id}', [ClinicsController::class, 'SinglePageDoctor']);
//Route::get('PlainingVisit/doctor_id={id}', [ClinicsController::class, 'PlainingVisit']);

////////////////////////// Cron Job

 Route::get('CronDeleteImageTable', [ImageDeletController::class, 'CronDeleteImageTable']);
