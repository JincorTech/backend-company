<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/17/17
 * Time: 8:19 PM
 */

namespace App\Applications\Company\Http\Requests\Employee;

use App\Core\Http\Requests\BaseAPIRequest;

class VerifyByCode extends BaseAPIRequest
{
    public function rules()
    {
        return [
            'verificationId' => 'required|string|size:36',
            'verificationCode' => 'required|string|size:6',
        ];
    }

    public function getVerificationId()
    {
        return $this->get('verificationId');
    }

    public function getVerificationCode()
    {
        return $this->get('verificationCode');
    }
}
