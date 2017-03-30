<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 30/03/2017
 * Time: 14:33
 */

namespace App\Applications\Company\Http\Requests\Company;

use App\Core\Http\Requests\GetAPIRequest;
use App\Domains\Employee\Entities\Employee;
use App;

class MyCompany extends GetAPIRequest
{

    public function authorize()
    {
        return $this->getUser() instanceof Employee;
    }

    public function rules()
    {
        return [];
    }

    /**
     * @return Employee|null
     */
    public function getUser()
    {
        return App::make('AppUser');
    }
}