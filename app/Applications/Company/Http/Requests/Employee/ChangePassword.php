<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/22/17
 * Time: 2:04 PM
 */

namespace App\Applications\Company\Http\Requests\Employee;


use App\Core\Http\Requests\BaseAPIRequest;
use App;

class ChangePassword extends BaseAPIRequest implements PasswordValidation
{

    public function rules()
    {
        return [
            'companyId' => 'required|string|size:36',
            'verificationId' => 'required_without:oldPassword|string|size:36',
            'oldPassword' => 'required_without:verificationId|string',
            'password' => [
                'required',
                'string',
                'min:8',
                self::PASSWORD_REGEX,
            ],
        ];
    }


    public function getCompanyId() : string
    {
        return $this->get('companyId');
    }

    public function getVerificationId()
    {
        return $this->get('verificationId');
    }

    public function getPassword()
    {
        return $this->get('password');
    }

    public function getOldPassword()
    {
        return $this->get('oldPassword');
    }

    public function authorize()
    {
        if ($this->getOldPassword()) {
            /** @var \App\Domains\Employee\Entities\Employee $employee */
            $employee = App::make('AppUser');
            if (!$employee->checkPassword($this->getOldPassword())) {
                return false;
            }
        }
        return true;
    }
}
