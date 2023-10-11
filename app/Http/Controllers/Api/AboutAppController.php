<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApplicationUse;
use App\Models\PrivacyPolicy;
use App\Models\TermsOfService;

class AboutAppController extends Controller
{

    /**
     * @OA\Get(
     * path="/api/get_ApplicationUse",
     * summary="get_ApplicationUse",
     * description="Правила пользования приложением",
     * operationId="get_ApplicationUse",
     * tags={"About_application"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Правила пользования приложением",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
    public function get_ApplicationUse(){
        $applicationUse = ApplicationUse::first();

        return response()->json([
            'status'=>true,
            'data'=>$applicationUse,
        ],200);
    }

    /**
     * @OA\Get(
     * path="/api/get_PrivacyPolicy",
     * summary="get_PrivacyPolicy",
     * description="Политика конфиденциальности",
     * operationId="get_PrivacyPolicy",
     * tags={"About_application"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Политика конфиденциальности",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */
    public function get_PrivacyPolicy(){
        $PrivacyPolicy = PrivacyPolicy::first();

        return response()->json([
            'status'=>true,
            'data'=>$PrivacyPolicy,
        ],200);
    }

    /**
     * @OA\Get(
     * path="/api/get_TermsOfService",
     * summary="get_TermsOfService",
     * description="Условия предоставления услуг",
     * operationId="get_TermsOfService",
     * tags={"About_application"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Условия предоставления услуг",
     *    @OA\JsonContent(
     *        )
     *     ),
     * )
     */

    public function get_TermsOfService(){
        $termsOfService = TermsOfService::first();

        return response()->json([
            'status'=>true,
            'data'=>$termsOfService,
        ],200);
    }
}
