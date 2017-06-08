<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 30/03/2017
 * Time: 13:11
 */

namespace App\Applications\Company\Http\Requests;


use App\Domains\Employee\Entities\Employee;
use App;

trait AuthenticatedUser
{

    public function authorize()
    {
        return $this->getUser() instanceof Employee;
    }


    /**
     * @return Employee
     */
    public function getUser()
    {
        try {
            return App::make('AppUser');

        } catch (\Exception $exception) {
            return null;
        }
    }

}