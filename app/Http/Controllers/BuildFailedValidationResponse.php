<?php
/**
 * Created by PhpStorm.
 * User: zhushiya
 * Date: 15/8/19
 * Time: 下午6:04
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

trait BuildFailedValidationResponse
{
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return new JsonResponse(['success' => 'N','msg' => $errors], 200);
    }
}