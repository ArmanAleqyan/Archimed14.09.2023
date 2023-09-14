<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDocumentType;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator;

class UpdateProfileController extends Controller
{

//    /**
//     * @OA\Get(
//     * path="/api/getDocumentsType",
//     * summary="getDocumentsType",
//     * description="Типы документа",
//     * operationId="getDocumentsType",
//     * tags={"MyCabinet"},
//     * @OA\RequestBody(
//     *    required=true,
//     *    @OA\JsonContent(
//     *    ),
//     * ),
//     * @OA\Response(
//     *    response=200,
//     *    description="getDocumentsType",
//     *    @OA\JsonContent(
//     *        )
//     *     )
//     * )
//     */
//
//    public function getDocumentsType(){
//
//
//        $getUser = UserDocument::where('user_id', auth()->user()->id)->get(['document_type']);
//
//
//        foreach ($getUser as $item) {
//          $a[] =   $item['document_type'];
//        }
//
//        $get = UserDocumentType::wherenotIn('id' ,$a)->get();
//
//
//
//        return response()->json([
//            'status' =>  true,
//            'data' => $get
//        ],200);
//    }


//    /**
//     * @OA\Get(
//     * path="/api/getUserPage",
//     * summary="getUserPage",
//     * description="Возрашает  информацыю  для  личного  кабинета",
//     * operationId="getUserPage",
//     * tags={"MyCabinet"},
//     * @OA\RequestBody(
//     *    required=true,
//     *    @OA\JsonContent(
//     *    ),
//     * ),
//     * @OA\Response(
//     *    response=200,
//     *    description="getUserPage",
//     *    @OA\JsonContent(
//     *        )
//     *     )
//     * )
//     */
//
//
//    public function getUserPage(){
//        $responseInfo = Http::withHeaders([
//            'Content-Type' => 'application/json',
//            'app-code' => 'com.postmodern.mobimedReact',
//            'Authorization' => 'Bearer '.auth()->user()->client_token,
//        ])->get('https://dev.mobimed.ru/Telemedialog/CentralService/user/info',
//            [])->json();
//
//        $user = User::with('UserDocument')->where('id',  auth()->user()->id)->get();
//
//        return response()->json([
//           'client_info' =>  $responseInfo,
//            'user' =>  $user
//        ]);
//    }

//    /**
//     * @OA\Post(
//     * path="/api/UpdateUser",
//     * summary="UpdateUser",
//     * description="Обнавлаем Авторизовонного пользвателя",
//     * operationId="UpdateUser",
//     * tags={"MyCabinet"},
//     * @OA\RequestBody(
//     *    required=true,
//     *    description="UpdateUser",
//     *    @OA\JsonContent(
//     *       @OA\Property(property="firstName", type="string", format="firstName", example="firstName"),
//     *       @OA\Property(property="lastName", type="string", format="lastName", example="lastName"),
//     *       @OA\Property(property="middleName", type="string", format="middleName", example="middleName"),
//     *       @OA\Property(property="phone", type="string", format="phone", example="phone"),
//     *       @OA\Property(property="gender", type="string", format="gender", example="gender"),
//     *       @OA\Property(property="birthDate", type="string", format="birthDate", example="birthDate"),
//     *       @OA\Property(property="snils", type="string", format="snils", example="snils"),
//     *       @OA\Property(property="dmsName", type="string", format="dmsName", example="dmsName"),
//     *       @OA\Property(property="dmsNumber", type="string", format="dmsNumber", example="dmsNumber"),
//     *       @OA\Property(property="dmsEndDate", type="string", format="dmsEndDate", example="dmsEndDate"),
//     *       @OA\Property(property="omsName", type="string", format="omsName", example="omsName"),
//     *       @OA\Property(property="omsNumber", type="string", format="omsNumber", example="omsNumber"),
//     *       @OA\Property(property="Citizenship", type="string", format="Citizenship", example="Citizenship"),
//     *       @OA\Property(property="Actual_Address", type="string", format="Actual_Address", example="Actual_Address"),
//     *       @OA\Property(property="Place_of_Study", type="string", format="Place_of_Study", example="Place_of_Study"),
//     *    ),
//     * ),
//     * @OA\Response(
//     *    response=200,
//     *    description="UpdateUser",
//     *    @OA\JsonContent(
//
//     *        )
//     *     )
//     * )
//     */
//
//
//    public function UpdateUser(Request $request){
//        $responseInfo = Http::withHeaders([
//            'Content-Type' => 'application/json',
//            'app-code' => 'com.postmodern.mobimedReact',
//            'Authorization' => 'Bearer '.auth()->user()->client_token,
//        ])->get('https://dev.mobimed.ru/Telemedialog/CentralService/user/info',
//            [])->json();
//        if(isset($request->firstName)){
//            $data['firstName'] = $request->firstName;
//        }else{
//            $data['firstName'] = $responseInfo['firstName'];
//        }
//        if(isset($request->lastName)){
//            $data['lastName'] = $request->lastName
//            ;
//        }else{
//            $data['lastName'] =   $responseInfo['lastName'] ;
//        }
//        if(isset($request->middleName)){
//            $data['middleName'] = $request->middleName;
//        }else{
//            $data['middleName'] = $responseInfo['middleName'];
//        }
//        if(isset($request->phone)){
//            $data['phone'] =  $request->phone;
//        }else{
//            $data['phone'] = $responseInfo['phone'];
//        }
//        if(isset($request->gender)){
//            $data['gender'] =  $request->gender;
//         }else{
//            $data['gender'] = $responseInfo['gender'];
//        }
//
//        if(isset($request->birthDate)){
//
//            $data['birthDate'] =$request->birthDate;
//        }else{
//            $data['birthDate'] =$responseInfo['birthDate'];
//        }
//
//        if(isset($request->snils)){
//            $data['snils'] = $request->snils;
//        }else{
//            $data['snils'] = $responseInfo['snils'];
//        }
//        if(isset($request->dmsName)){
//            $data['dmsName'] = $request->dmsName;
//        }else{
//            $data['dmsName'] = $responseInfo['dmsName'];
//        }
//        if(isset($request->dmsNumber)){
//            $data['dmsNumber'] = $request->dmsNumber;
//        }else{
//            $data['dmsNumber'] = $responseInfo['dmsNumber'];
//        }
//        if(isset($request->dmsEndDate)){
//            $data['dmsEndDate'] =$request->dmsEndDate;
//        }else{
//
//            $data['dmsEndDate'] = $responseInfo['dmsEndDate'];
//        }
//        if(isset($request->omsName)){
//            $data['omsName'] = $request->omsName;
//        }else{
//            $data['omsName'] = $responseInfo['omsName'];
//        }
//        if(isset($request->omsNumber)){
//            $data['omsNumber'] =$request->omsNumber;
//        }else{
//            $data['omsNumber'] = $responseInfo['omsNumber'];
//        }
//
//
//
//        $updateUserProfile = Http::withHeaders([
//            'Content-Type' => 'application/json',
//            'app-code' => 'com.postmodern.mobimedReact',
//            'Authorization' => 'Bearer '.auth()->user()->client_token,
//        ])->post('https://dev.mobimed.ru/Telemedialog/CentralService/user/info',
//            [
//                'firstName' =>  $data['firstName'],
//                'lastName' => $data['firstName'],
//                'middleName' => $data['firstName'],
//                'phone' => $data['phone'],
//                'gender' => $data['gender'],
//                'birthDate' => $data['birthDate'],
//                'snils' => $data['snils'],
//                'dmsName' => $data['dmsName'],
//                'dmsNumber' => $data['dmsNumber'],
//                'dmsEndDate' => $data['dmsEndDate'],
//                'omsName' => $data['omsName'],
//                'omsNumber' => $data['omsNumber']
//             ])->json();
//
//       if(isset($updateUserProfile['requiresEmailConfirmation'])){
//          User::where('id', auth()->user()->id)->update([
//             'firstName' => $data['firstName'],
//              'middleName' => $data['middleName'],
//              'lastName' => $data['lastName'],
//              'gender' => $data['gender'],
//              'birthDate' => $data['birthDate'],
//              'Place_of_Study' => $request->Place_of_Study,
//              'Actual_Address' =>  $request->Actual_Address,
//              'Citizenship' => $request->Citizenship
//          ]);
//           return response()->json([
//               'status' => true,
//               'nextUrl' =>  'api/getUserPage',
//           ],200);
//       }else{
//          return response()->json([
//              'status' => false,
//              'message' =>  'client error',
//              'client_message' => $updateUserProfile
//              ],422);
//       }
//    }

//    /**
//     * @OA\Post(
//     * path="/api/UserAddNewDocument",
//     * summary="UserAddNewDocument",
//     * description="Добавлаем  Новый Документ к Пользвателю",
//     * operationId="UserAddNewDocument",
//     * tags={"MyCabinet"},
//     * @OA\RequestBody(
//     *    required=true,
//     *    description="UpdateUser",
//     *    @OA\JsonContent(
//     *       @OA\Property(property="document_type", type="string", format="document_type", example="document_type"),
//     *       @OA\Property(property="document_number", type="string", format="document_number", example="document_number"),
//     *       @OA\Property(property="document_by", type="string", format="document_by", example="document_by"),
//     *       @OA\Property(property="document_date", type="string", format="document_date", example="document_date"),
//     *    ),
//     * ),
//     * @OA\Response(
//     *    response=200,
//     *    description="UserAddNewDocument",
//     *    @OA\JsonContent(
//
//     *        )
//     *     )
//     * )
//     */
//
//
//    public function UserAddNewDocument(Request $request){
//
//         $get_documenTable =  UserDocument::where('user_id', auth()->user()->id)->where('document_type',  $request->document_type)->get();
//         if(!$get_documenTable->isEMpty()){
//             return response()->json([
//                'status' =>  false,
//                'message' =>  'you have this document type'
//             ],422);
//         }
//        $rules=array(
//            'document_type' => 'required',
//        );
//        $validator=Validator::make($request->all(),$rules);
//        if($validator->fails())
//        {
//            return $validator->errors();
//        }
//         if($request->document_type == 1 || $request->document_type == 2){
//             $rules=array(
//                 'document_number' => 'required',
//                 'document_by' => 'required',
//                 'document_date' => 'required',
//             );
//             $validator=Validator::make($request->all(),$rules);
//             if($validator->fails())
//             {
//                 return $validator->errors();
//             }
//         }else{
//             $rules=array(
//                 'document_number' => 'required',
//             );
//             $validator=Validator::make($request->all(),$rules);
//             if($validator->fails())
//             {
//                 return $validator->errors();
//             }
//         }
//         $getDucumentType = UserDocumentType::where('id', $request->document_type)->first();
//         if($getDucumentType == null){
//             return response()->json([
//                    'status' =>  false,
//                    'message' => 'WRONG DOCUMENT id'
//             ],422);
//         }
//         $create = UserDocument::insert([
//            'user_id' =>  auth()->user()->id,
//             'document_type' =>  $request->document_type,
//             'document_name' => $getDucumentType->type,
//             'document_number'=> $request->document_number,
//             'document_by' => $request->document_by,
//             'document_date' => $request->document_date,
//         ]);
//
//         return response()->json([
//            'status' =>  true,
//             'message' => 'Document Added',
//             'nextUrl' => '/api/getUserPage'
//         ],200);
//
//
//    }
}
