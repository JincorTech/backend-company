<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 29/03/2017
 * Time: 17:32
 */

namespace App\Applications\Company\Transformers\Employee;


use App\Domains\Employee\Entities\Employee;
use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;

/**
 * Class Colleague
 * @package App\Applications\Company\Transformers\Employee
 *
 * @TODO: implement transformation
 */
class EmployeeList extends TransformerAbstract
{

    public function transform(Collection $employeeList)
    {
        $employeeTransformer = new Colleague();
        $emplArr = [];
        /** @var Employee $employee */
        foreach ($employeeList as $employee) {
            $emplArr[$employee->getLogin()] = $employeeTransformer->transform($employee);
        }

        return [
            'data' => $emplArr
        ];

    }

}
