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

class Register extends BaseAPIRequest implements PasswordValidation
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'firstName' => 'required|string|min:2',
            'lastName' => 'required|string|min:2',
            'password' => [
                'required',
                'string',
                'min:8',
                self::PASSWORD_REGEX,
            ],
            'email' => 'required|email',
            'position' => 'required|string|min:2|max:60',
            'token' => 'required|string',
        ];
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->get('firstName');
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->get('position');
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->get('lastName');
    }


    /**
     * Company token
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->get('token');
    }

    /**
     * @return EmployeeProfile
     */
    public function getProfile(): EmployeeProfile
    {
        return new EmployeeProfile($this->getFirstName(), $this->getLastName(), $this->getPosition());
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->get('password');
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->get('email');
    }
}
