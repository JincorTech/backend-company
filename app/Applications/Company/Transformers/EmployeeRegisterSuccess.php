<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/21/17
 * Time: 11:14 PM
 */

namespace App\Applications\Company\Transformers;

use App\Applications\Company\Transformers\Employee\SelfProfile;
use App\Domains\Employee\Entities\Employee;
use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;

class EmployeeRegisterSuccess extends TransformerAbstract
{
    public function transform(Collection $data)
    {
        /** @var Employee $employee */
        $employee = $data['employee'];
        return [
            'data' => [
                'employee' => (new SelfProfile())->transform($employee),
                'token' => $data['token'],
                'verificationId' => $data['verificationId'],
            ],
        ];
    }
}
