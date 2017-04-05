<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 05/04/2017
 * Time: 15:48
 */

namespace App\Applications\Company\Http\Requests\Company;


use App\Applications\Company\Http\Requests\AuthenticatedUser;
use App\Core\Http\Requests\GetAPIRequest;

class MyCompanyRequest extends GetAPIRequest
{

    use AuthenticatedUser;

    public function rules() : array
    {
        return [];
    }

}