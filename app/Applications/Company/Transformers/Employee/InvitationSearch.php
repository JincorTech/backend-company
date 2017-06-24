<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 21.06.17
 * Time: 16:34
 */

namespace App\Applications\Company\Transformers\Employee;

use League\Fractal\TransformerAbstract;
use App\Domains\Employee\Entities\EmployeeVerification;

class InvitationSearch extends TransformerAbstract
{
    public function transform(EmployeeVerification $employeeVerification)
    {
        return [
            'id' => $employeeVerification->getId(),
            'email' => $employeeVerification->getEmail(),
        ];
    }
}
