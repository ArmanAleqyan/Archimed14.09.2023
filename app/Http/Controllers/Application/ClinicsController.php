<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Clinics;
use App\Models\DoctorSubject;
use App\Models\DoctorService;
use App\Models\DoctorList;
use App\Models\DoctorVisitKinds;
use App\Models\DoctorVisitOrder;
use App\Models\MedicalTest;
use App\Models\MedicalTestParametr;
use App\Models\HomeService;
use Validator;

use App\Models\ObsClinic;
use App\Models\ObsClinicService;


class ClinicsController extends Controller
{

    public function newClinics(){
        $client_token = env('token');





        $pdf = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $client_token",
        ])->timeout(5000000)->post(env('PAM_URL').'LK_GET_RESULT_BY_PATDIREC',
            [
                    "patdirec_id" => 5611456
            ])->json();


        $obsledovanie = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $client_token",
        ])->timeout(5000000)->post(env('PAM_URL').'VIEW_LK_OBSLEDOVANIE',
            [
            ])->json();


        foreach ($obsledovanie['OBSLEDOVANIE'] as $obs){

            $create_obs = ObsClinic::UpdateOrCreate(['NAME' => $obs['NAME']],[
                'NAME' => $obs['NAME']
            ]);
            if (isset($obs['SERVS'])){
                foreach ($obs['SERVS'] as $servs)
                ObsClinicService::updateOrcreate([
                    'CODE' =>$servs['CODE']
                ],[
                    'obs_id' => $create_obs->id,
                    'CODE' => $servs['CODE'],
                    'LABEL' => $servs['LABEL'],
                    'SHORT_LABEL' => $servs['SHORT_LABEL']??null,
                    'DESCRIPTION' => $servs['DESCRIPTION']??null,
                    'PRICE' => $servs['PRICE']??null,
                    'FILIAL_ID' => $servs['FILIAL_ID'],
                    'FILIAL_CODE' => $servs['FILIAL_CODE'],
                ]);
            }
        }
        $labarator_service = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $client_token",
        ])->timeout(5000000)->post('https://apitest.arhimedlab.com/VIEW_LK_LAB_EXAMS',
            [
            ])->json();
        foreach ($labarator_service['result'] as $item) {
            $create_medical_test =     MedicalTest::UpdateOrCreate([ 'PL_EXAM_ID' => $item['PL_EXAM_ID']],[
                'NAME' => $item['NAME'],
                'PL_EXAM_ID' => $item['PL_EXAM_ID'],
                'PRINT_MEMO' => $item['PRINT_MEMO']??null,
                'GROUP_DIR' => $item['GROUP_DIR']??null,
            ]);
            $json =json_decode($item['SERV']);
            foreach ($json as $serv){
                MedicalTestParametr::UpdateOrcreate(['CODE' => $serv->CODE],
                    [
                        'test_id' => $create_medical_test->id,
                        'CODE' => $serv->CODE,
                        'LABEL' => $serv->LABEL,
                        'price' => $serv->price,
                        'biotype' => $serv->biotype??null,
                        'conttype' => $serv->conttype??null,
                        'clinic_id' => $serv->FM_ORG_ID??null,
                        'description_serv' => $serv->description_serv??null,
                    ]
                );
            }
        }
        $home_service = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $client_token",
        ])->timeout(5000000)->post('https://apitest.arhimedlab.com/VIEW_LK_SERV_HOME',
            [
            ])->json();



        foreach ($home_service['result'] as $home ){
//                    dump( $home['FILIAL_ID']);
            HomeService::updateOrCreate(['CODE' => $home['CODE']],[
               'CODE' => $home['CODE']??null,
               'LABEL' => $home['LABEL']??null,
               'SHORT_LABEL' => $home['SHORT_LABEL']??null,
               'DESCRIPTION' => $home['DESCRIPTION']??null,
               'SERV_GRP_LEVEL1' => $home['SERV_GRP_LEVEL1']??null,
               'SERV_GRP_LEVEL2' => $home['SERV_GRP_LEVEL2']??null,
               'PRICE' => $home['PRICE']??null,
               'FILIAL_ID' => $home['FILIAL_ID']??null,
               'FILIAL_CODE' => $home['FILIAL_CODE']??null,
               'REGION' => $home['REGION']??null,
            ]);
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $client_token",
        ])->get('https://apitest.arhimedlab.com/VIEW_LK_FILIALS',
            [
            ])->json();

        foreach ($response['result'] as $UpOrCrCl ){
           Clinics::updateOrCreate([
               'clinic_id' => $UpOrCrCl['fm_org_id']
           ],[
              'clinic_id' => $UpOrCrCl['fm_org_id'],
               'address' => $UpOrCrCl['address'],
               'ville' => $UpOrCrCl['VILLE'],
               'inn' => $UpOrCrCl['INN'],
               'full_label' => $UpOrCrCl['FULL_LABEL'],
               'phone' => $UpOrCrCl['phone'],
               'email' => $UpOrCrCl['email'],
               'SHORT_LABEL' => $UpOrCrCl['SHORT_LABEL'],
               'description' => $UpOrCrCl['DESCRIPTION'],
                'CLINIC_WORK_TIME' => $UpOrCrCl['CLINIC_WORK_TIME'],
               'coordinates' => $UpOrCrCl['CLINIC_COORDINATES'],
           ]);
        }



        $doctor = Http::withHeaders([
            'Content-Type' => 'application/json',
                'Authorization' => "Basic $client_token",
        ])->get('https://apitest.arhimedlab.com/VIEW_LK_MED_EXAMS',
            [
            ])->json();


        foreach ($doctor['result'] as $doc){
            if (isset($doc['DESCRIPT'])){
                $desc = $doc['DESCRIPT'];
            }else{
                $desc = null;
            }

         $create_doctor =   DoctorList::updateOrcreate(['MEDECINS_ID'=>$doc['MEDECINS_ID']],[
                'MEDECINS_ID' =>$doc['MEDECINS_ID'],
                'med_arch' =>$doc['med_arch'],
                'Fam_doctor' =>$doc['Fam_doctor'],
                'om_doctor' =>$doc['om_doctor'],
                'FIO' =>$doc['FIO'],
                'DESCRIPT' =>$desc,
                'photo' => $doc['Photo_b64']??null,
                'experience' => $doc['Experience']??null
            ]);



            foreach ($doc['PL_SUBJ'] as $subject){
              $create_subject =  DoctorSubject::Updateorcreate(['doctor_id'=> $create_doctor->id],[
                  'doctor_id'=> $create_doctor->id,
                    'PL_SUBJ_ID' => $subject['PL_SUBJ_ID'],
                    'subj_name' => $subject['subj_name'],
                    'subj_arch' => $subject['subj_arch'],
                    'VILLE' => $subject['VILLE'],
                ]);
              foreach ($subject['PL_EXAM'] as $service){
                    if (isset( $service['priceAmount'])){
                        $priceamount = $service['priceAmount'];
                    }else{
                        $priceamount = null;
                    }
                  DoctorService::updateOrcreate(['PL_EXAM_ID' => $service['PL_EXAM_ID'],'doctor_id'=> $create_doctor->id ],[
                       'doctor_id' => $create_doctor->id,
                        'subject_id' => $create_subject->id,
                        'PL_EXAM_ID' => $service['PL_EXAM_ID'],
                        'name_exam' => $service['name_exam'],
                        'specialisation_name' => $service['specialisation_name'],
                        'EXAM_ORDER' => $service['EXAM_ORDER'],
                        'DUREE' => $service['DUREE'],
                        'priceAmount' => $priceamount,
                        'FM_INTORG_ID' => $service['FM_INTORG_ID'],
                  ]);
              }
            }
        }
    }



    /**
     * @OA\Get(
     * path="/api/doctorsList",
     * summary="DoctorsListc",
     * description="Возвращает все данные для Листа  докторов    ",
     * operationId="DoctorsListc",
     * tags={"Doctors"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="filtr", type="string", format="text", example="true"),
     *       @OA\Property(property="city", type="string", format="text", example="Москва"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="DoctorsListc",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */

    public function doctorsList(Request $request){
        $city = Clinics::orderBy('id', 'desc')->get();
        if(isset($request->city)){
            $getMoskov = Clinics::where('address', 'like', '%'.$request->city.'%')->get();
        }else{
            $getMoskov = Clinics::where('address', 'like', '%'.'Москва'.'%')->get();
        }
        foreach ($getMoskov as $item) {
            $city_id[] = $item['clinic_id'];
        }
        foreach ($city as $citys){
            $explode = explode(',', $citys['address']);
            $cityr[] = [
              'clinic_id' => $citys['clinic_id'],
              'city_name' => $explode[0]
            ];
        }
        if (isset($request->filtr)){
            $doctor = Doctor::OrderBy('plSubjName', 'asc')->whereIn('clinicId', $city_id)->simplepaginate(10);
        }else{
            $doctor = Doctor::OrderBy('id', 'asc')->whereIn('clinicId', $city_id)->simplepaginate(10);

        }
        return response()->json([
           'status' => true,
           'doctors' =>  $doctor,
            'city' =>  $cityr
        ],200);
    }

    /**
     * @OA\Get(
     * path="/api/SinglePageDoctor/doctor_id=124567",
     * summary="SinglePageDoctor",
     * description="Возвращает все данные Одного  доктора    ",
     * operationId="SinglePageDoctor",
     * tags={"Doctors"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(

     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="DoctorsListc",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */


    public function SinglePageDoctor(Request $request,$id){
        //->where('id', $id)->first();
            $getDoctor = DoctorList::with('DoctorService','DoctorService.get_order','DoctorService.get_services_review')->where('id', $id)->first();
            return response()->json([
               'status' =>  true,
               'data' => $getDoctor,
            ],200);
    }


//    /**
//     * @OA\Get(
//     * path="/api/PlainingVisit/doctor_id=124567",
//     * summary="PlainingVisit",
//     * description="планируем  визит  ",
//     * operationId="PlainingVisit",
//     * tags={"VisitDoctors"},
//     * @OA\RequestBody(
//     *    required=true,
//     *    @OA\JsonContent(
//     *        @OA\Property(property="visitKindId", type="string", format="text", example="152"),
//     *       @OA\Property(property="city", type="string", format="text", example="Москва"),
//     *       @OA\Property(property="date", type="string", format="text", example="20051212"),
//     *    ),
//     * ),
//     * @OA\Response(
//     *    response=200,
//     *    description="DoctorsListc",
//     *    @OA\JsonContent(
//     *        )
//     *     )
//     * )
//     */
//
//
//    public function PlainingVisit(Request $request,$id){
//        $getDoctor = Doctor::with('DocotrsVisit')->where('doctors_id', $id)->get();
//        $visit = DoctorVisitKinds::where('kinds_id', $request->visitKindId)->get();
//
//        if($getDoctor->isEMpty()){
//            return response()->json([
//               'status' =>  false,
//               'message' =>  'wrong Docotor_id'
//            ]);
//        }
//        if(isset($request->city)){
//            $getMoskov = Clinics::where('address', 'like', '%'.$request->city.'%')->get();
//        }else{
//            $getMoskov = Clinics::where('address', 'like', '%'.'Москва'.'%')->get();
//        }
//        foreach ($getMoskov as $item) {
//            $city_id[] = $item['clinic_id'];
//        }
//        $response = [];
//        foreach ($getMoskov as $citys){
//            $explode = explode(',', $citys['address']);
//            $response[] = [  'clinic_id' => $citys->clinic_id , 'address' => $citys->address , 'clock' => Http::withHeaders([
//                'Content-Type' => 'application/json',
//                'app-code' =>  env('Medialog_Code'),
//            ])->get('https://dev.mobimed.ru/Telemedialog/CentralService/planning/doctorsTimeSlots',
//                [
//                    'clinicId' => $citys->clinic_id,
//                    'doctorId' => $id,
//                    'visitKindId' => $request->visitKindId,
//                    'date' => $request->date,
//                    'plSubjId' =>  $getDoctor[0]->plSubjId,
//                ])->json()];
//        }
//
//
//             return response()->json([
//                 'status' => true,
//                 'clock' => $response,
//                 'kinds_info'  =>  $visit
//             ],200);
//    }

}
