<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/22/17
 * Time: 1:39 PM
 */

namespace App\Applications\Company\Http\Requests\Employee;


use App\Core\Http\Requests\BaseAPIRequest;

class SendRestorePasswordEmail extends BaseAPIRequest
{

    public function rules()
    {
        return [
            'email' => 'required|email'
        ];
    }

    public function getEmail()
    {
        return $this->get('email');
    }

}