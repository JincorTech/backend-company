<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/17/17
 * Time: 8:18 PM
 */

namespace App\Applications\Company\Http\Requests\Employee;

use App\Core\Http\Requests\GetAPIRequest;

class SendVerificationCode extends GetAPIRequest
{
    public function rules()
    {
        return [
            'verificationId' => 'required|string|size:36',
        ];
    }

    public function getEmail()
    {
        return $this->get('email');
    }

    public function getPhone()
    {
        return $this->get('phone');
    }

    public function getVerificationId()
    {
        return $this->get('verificationId');
    }
}
