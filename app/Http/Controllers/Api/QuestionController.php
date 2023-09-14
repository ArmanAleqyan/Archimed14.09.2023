<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{

    /**
     * @OA\Get(
     * path="/api/get_question",
     * summary="get_question",
     * description="Вапрос и ответ",
     * operationId="get_question",
     * tags={"Question"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Вапрос и ответ",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
    public function get_question(){
        $question = Question::all();

        return $question;
    }
}
