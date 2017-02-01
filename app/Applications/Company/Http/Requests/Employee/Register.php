<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/19/17
 * Time: 5:46 PM
 */

namespace App\Applications\Company\Http\Requests\Employee;

use App\Core\Http\Requests\BaseAPIRequest;
use App\Domains\Employee\ValueObjects\EmployeeProfile;

class Register extends BaseAPIRequest
{
    public function rules()
    {
        return [
            'firstName' => 'required|string|min:2',
            'lastName' => 'required|string|min:2',
            'password' => 'required|string|min:6',
            'position' => 'required|string|min:2',
            'verificationId' => 'required|string|size:36',
        ];
    }

    public function getFirstName() : string
    {
        return $this->get('firstName');
    }

    public function getPosition() : string
    {
        return $this->get('position');
    }

    public function getLastName() : string
    {
        return $this->get('lastName');
    }

    public function getVerificationId() : string
    {
        return $this->get('verificationId');
    }

    public function getProfile() : EmployeeProfile
    {
        return new EmployeeProfile($this->getFirstName(), $this->getLastName(), $this->getPosition());
    }

    public function getPassword() : string
    {
        return $this->get('password');
    }
}
