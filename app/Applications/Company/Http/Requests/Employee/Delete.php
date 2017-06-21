<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 19/04/2017
 * Time: 19:40
 */

namespace App\Applications\Company\Http\Requests\Employee;


use App\Applications\Company\Http\Requests\AdminUser;
use App\Core\Http\Requests\GetAPIRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Delete extends GetAPIRequest
{

    use AdminUser;

    public function rules() : array
    {
        return [];
    }

    protected function failedAuthorization()
    {
        throw new HttpException(403, trans('exceptions.employee.access_denied'));
    }
}