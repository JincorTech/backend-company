<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 12/7/16
 * Time: 4:26 PM
 */

namespace App\Applications\Company\Http\Requests;

use App\Core\Http\Requests\BaseAPIRequest;

class RegisterEmployeeRequest extends BaseAPIRequest
{
    public function rules()
    {
        return [
            'firstName' => 'required|string|min:2|max:30',
            'lastName' => 'required|string|min:2|max:30',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/',
            'passwordConfirmation' => 'required|same:password',
            'phoneActivationId' => 'required',
            'phoneActivationCode' => 'required|digits:6',
            'emailActivationId' => 'required',
            'emailActivationCode' => 'required|digits:6',
        ];
    }
}
