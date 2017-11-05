<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 05/11/2017
 * Time: 14:40
 */

namespace App\Applications\Company\Exceptions\Employee;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class EmployeeNotActivated extends UnauthorizedHttpException
{

    public function __construct()
    {
        $challenge = 'Bearer realm="Jincor"';
        parent::__construct($challenge, trans('exceptions.employee.inactive'));
    }

}
    