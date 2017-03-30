<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 30/03/2017
 * Time: 13:10
 */

namespace App\Applications\Company\Http\Requests\Employee;


use App\Applications\Company\Http\Requests\AuthenticatedUser;
use App\Core\Http\Requests\GetAPIRequest;

class Colleagues extends GetAPIRequest
{

    use AuthenticatedUser;

    public function rules()
    {
        return [];
    }

}