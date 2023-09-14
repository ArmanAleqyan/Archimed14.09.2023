<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SearchResults;
use Validator;
use App\Models\AnalizePageShow;
use App\Models\CategoryPageShow;
use App\Models\DoctorList;
use App\Models\MedicalTest;


class SearchController extends Controller
{

    /**
     * @OA\Post(
     * path="/api/search",
     * summary="SearchText",
     * description="после окончание ввода поля поиска отправляем данные на этот API",
     * operationId="SearchText",
     * tags={"Search"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="text", type="string", format="text", example="Search Result"),
     *
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Create Search Result",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */

    public function search(Request $request){

        $rules=array(
            'text' => 'required|max:254',
        );
        $validator=Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            return $validator->errors();
        }

        $search_value = explode(" ", $request->text);

        $get_doctor = DoctorList::query();
        foreach ($search_value as $doctor) {
            $get_doctor->orWhere(function ($query) use ($doctor) {
                $query
                    ->where('Fam_doctor', 'like', "%{$doctor}%")
                    ->orwhere('om_doctor', 'like', "%{$doctor}%")
                    ->orwhere('FIO', 'like', "%{$doctor}%")
                    ->orwhere('DESCRIPT', 'like', "%{$doctor}%");
            });
        }

        $medical_analizy_complex = MedicalTest::query();

        foreach ($search_value as $medical_analize_compl) {
            $medical_analizy_complex->orWhere(function ($query) use ($medical_analize_compl) {
                $query
                    ->where('NAME', 'like', "%{$medical_analize_compl}%")
                    ->orwhere('PRINT_MEMO', 'like', "%{$medical_analize_compl}%");
            });
        }
        $medical_analizy_complex = $medical_analizy_complex->paginate(10);
        $get_doctor = $get_doctor->paginate(10);
            if (auth()->user() != null){
                SearchResults::create([
                    'user_id' => auth()->user()->id,
                    'text' => $request->text,
                ]);
            }else{
                SearchResults::create([
                    'text' => $request->text,
                ]);
            }


    return response()->json([
       'status' => true,
       'message' => 'Search Text Created',
        'doctors_list' => $get_doctor,
        'analize_complex' => $medical_analizy_complex
    ], 200);
    }


    /**
     * @OA\Post(
     * path="/api/CreateAnalisePage",
     * summary="CreateAnalisePage",
     * description="При открытие страницы анализа отправляем имя анализа",
     * operationId="CreateAnalisePage",
     * tags={"Search"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="text", type="string", format="text", example="Analize Name"),
     *
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Analise Text Created",
     *    @OA\JsonContent(

     *        )
     *     )
     * )
     */

    public function CreateAnalisePage(Request $request){
        $rules=array(
            'text' => 'required|max:254',
        );
        $validator=Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            return $validator->errors();
        }

        AnalizePageShow::create([
            'analise_name' => $request->text
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Analise Text Created'
        ], 200);
    }


    /**
     * @OA\Post(
     * path="/api/CreateCategoryPage",
     * summary="CreateCategoryPage",
     * description="При открытие страницы анализа отправляем имя анализа",
     * operationId="CreateCategoryPage",
     * tags={"Search"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       @OA\Property(property="text", type="string", format="text", example="Category"),
     *
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Category Text Created",
     *    @OA\JsonContent(
     *        )
     *     )
     * )
     */


    public function CreateCategoryPage(Request $request){
        $rules=array(
            'text' => 'required|max:254',
        );
        $validator=Validator::make($request->all(),$rules);

        if($validator->fails())
        {
            return $validator->errors();
        }
        CategoryPageShow::create([
            'category_name' => $request->text
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category Text Created'
        ], 200);

    }
}
