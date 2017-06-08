<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 29/03/2017
 * Time: 17:22
 */

namespace App\Applications\Company\Http\Requests\Employee;


use App\Core\Http\Requests\GetAPIRequest;
use App\Applications\Company\Http\Requests\AuthenticatedUser;

class Me extends GetAPIRequest
{

    use AuthenticatedUser;

    public function rules()
    {
        return [];
    }

}