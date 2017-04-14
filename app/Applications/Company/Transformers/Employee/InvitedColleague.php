<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 14/04/2017
 * Time: 07:55
 */

namespace App\Applications\Company\Transformers\Employee;


use App\Domains\Employee\Entities\EmployeeVerification;
use League\Fractal\TransformerAbstract;

class InvitedColleague extends TransformerAbstract
{

    public function transform(EmployeeVerification $employeeVerification)
    {
        return [
            'contacts' => [
                'email' => $employeeVerification->getEmail(),
            ],
            'meta' => [
                'status' => 'invited',
                'invited_at' => $employeeVerification->getCreatedAt()->format(\DateTime::ISO8601),
            ]
        ];
    }


}