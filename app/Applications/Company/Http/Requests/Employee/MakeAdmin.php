<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 19/04/2017
 * Time: 18:10
 */

namespace App\Applications\Company\Http\Requests\Employee;


use App\Applications\Company\Http\Requests\AdminUser;
use App\Core\Http\Requests\BaseAPIRequest;
use App\Domains\Employee\Entities\Employee;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MakeAdmin extends BaseAPIRequest
{

   use AdminUser;

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'id' => 'string|size:36',
            'value' => 'boolean',
        ];
    }

    protected function failedAuthorization()
    {
        throw new HttpException(403, trans('exceptions.employee.access_denied'));
    }

}