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
            'email' => 'string|email|required_with:password',
            'password' => 'string|required_with:email',
            'verificationId' => 'string|size:36|required_without:email,password',
        ];
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->get('email');
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->get('password');
    }

    /**
     * @return string|null
     */
    public function getVerificationId()
    {
        return $this->get('verificationId');
    }
}
