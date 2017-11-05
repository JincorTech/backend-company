<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 20/04/2017
 * Time: 11:16
 */

namespace App\Applications\Company\Exceptions\Employee;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class PermissionDenied extends AccessDeniedHttpException
{

    public function __construct()
    {
        parent::__construct(trans('exceptions.employee.access_denied'));
    }
}
