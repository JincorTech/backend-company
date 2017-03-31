<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 30/03/2017
 * Time: 14:33
 */

namespace App\Applications\Company\Http\Requests\Company;

use App\Applications\Company\Http\Requests\AuthenticatedUser;
use App\Domains\Employee\Entities\Employee;
use App\Core\Http\Requests\GetAPIRequest;
use App;

class MyCompany extends GetAPIRequest
{

   use AuthenticatedUser;

    public function rules()
    {
        return [];
    }

}