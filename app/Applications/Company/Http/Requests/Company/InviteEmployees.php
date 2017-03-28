<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/18/17
 * Time: 4:12 PM
 */

namespace App\Applications\Company\Http\Requests\Company;


use App\Core\Http\Requests\BaseAPIRequest;
use App;
use Illuminate\Auth\AuthenticationException;

class InviteEmployees extends BaseAPIRequest
{

    /**
     * @return bool
     */
    public function authorize()
    {
        /** @var \App\Domains\Employee\Entities\Employee $employee */
        try {
            $employee = App::make('AppUser');
        } catch (\Exception $exception) {
            throw new AuthenticationException();
        }
        return $employee && $employee->isAdmin();
    }


    public function rules()
    {
        return [
            'emails' =>  'required|array',
        ];
    }

    public function getEmails() : array
    {
        return $this->get('emails');
    }
}