<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 29/03/2017
 * Time: 17:31
 */

namespace App\Applications\Company\Transformers\Employee;


use App\Domains\Employee\Entities\Employee;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * Class SelfProfile
 * @package App\Applications\Company\Transformers\Employee
 *
 * Profile of authenticated user
 *
 */
class SelfProfile extends TransformerAbstract
{

    public function transform(Employee $employee)
    {
        return [
            'id' => $employee->getId(),
            'profile' => $this->getProfile($employee),
            'contacts' => $this->getContacts($employee),
            'meta' => $this->getMeta($employee),
        ];
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
            'invited_at' => Carbon::now(),
            'registered_at' => Carbon::now(),
        ];
    }

    /**
     * @param Employee $employee
     * @return array
     */
    private function getContacts(Employee $employee)
    {
        return [
            'email' => $employee->getContacts()->getEmail(),
            'phone' => $employee->getContacts()->getPhone(),
        ];
    }

    /**
     * @param Employee $employee
     * @return array
     */
    private function getProfile(Employee $employee)
    {
        return [
            'name' => $employee->getProfile()->getName(),
            'position' => $employee->getProfile()->getPosition(),
            'avatar' => 'http://i.imgur.com/n613Ki4.jpg', //TODO
        ];
    }

}