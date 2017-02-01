<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/1/17
 * Time: 5:49 PM
 */

namespace App\Applications\Company\Http\Requests\Employee;

use App\Core\Http\Requests\GetAPIRequest;

class MatchingCompanies extends GetAPIRequest
{
    public function rules()
    {
        return [
            'email' => 'string|email|required',
            'password' => 'string|required',
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
}
