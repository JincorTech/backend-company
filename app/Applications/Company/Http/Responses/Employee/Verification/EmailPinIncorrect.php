<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 17/10/2017
 * Time: 19:33
 */

namespace App\Applications\Company\Http\Responses\Employee\Verification;

use Illuminate\Http\JsonResponse;

class EmailPinIncorrect extends JsonResponse
{

    public function __construct()
    {
        parent::__construct([
            'success' => false,
            'message' => trans('exceptions.verification.code.incorrect'),
        ], 401);
    }

}