<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 29/03/2017
 * Time: 17:22
 */

namespace App\Applications\Company\Http\Requests\Employee;


use App\Domains\Employee\Entities\Employee;
use App\Core\Http\Requests\GetAPIRequest;
use App;

class Me extends GetAPIRequest
{

    public function authorize()
    {
        if ($this->getUser() instanceof Employee) {
            return true;
        }
        return false;
    }


    /**
     * @return Employee
     */
    public function getUser()
    {
        return App::make('AppUser');
    }

    public function rules()
    {
        return [];
    }

}