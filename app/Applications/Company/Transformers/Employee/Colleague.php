<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 29/03/2017
 * Time: 17:32
 */

namespace App\Applications\Company\Transformers\Employee;


use App\Domains\Employee\Entities\Employee;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * Class Colleague
 * @package App\Applications\Company\Transformers\Employee
 *
 * @TODO: implement transformation
 */
class Colleague extends SelfProfile
{

    public function transform(Employee $employee)
    {
        return array_merge(
            parent::transform($employee),
            ['meta' => $this->getMeta($employee)]
        );
    }

    /**
     * @param Employee $employee
     * @return array
     */
    private function getMeta(Employee $employee)
    {
        //TODO
        return [
            'status' => 'active',
            'invited_at' => Carbon::now()->toIso8601String(),
            'registered_at' => Carbon::now()->toIso8601String(),
        ];
    }

}