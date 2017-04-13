<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 14/04/2017
 * Time: 03:21
 */

namespace App\Applications\Company\Transformers\Employee;


use App\Domains\Employee\Entities\Employee;
use League\Fractal\TransformerAbstract;
use StdClass;

class LoginResponse extends TransformerAbstract
{

    public function transform(StdClass $data)
    {
        $data = (array) $data;
        return [
            'employee' => $this->transformEmployee($data['employee']),
            'token' => $data['token']
        ];
    }

    private function transformEmployee(Employee $employee)
    {
        return (new SelfProfile())->transform($employee);
    }

}