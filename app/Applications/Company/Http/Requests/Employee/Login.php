<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/31/17
 * Time: 1:38 PM
 */

namespace App\Applications\Company\Http\Requests\Employee;

use App\Core\Http\Requests\BaseAPIRequest;

class Login extends BaseAPIRequest
{
    public function rules()
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required',
            'companyId' => 'string|size:36',
        ];
    }

    public function getEmail() : string
    {
        return $this->get('email');
    }

    public function getPassword() : string
    {
        return $this->get('password');
    }

    public function getCompanyId()
    {
        return $this->get('companyId');
    }
}
