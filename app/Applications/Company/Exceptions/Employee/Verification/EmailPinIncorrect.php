<?php
/**
 * Created by PhpStorm.
 * User: Artemii
 * Date: 08.06.2017
 * Time: 20:15
 */

namespace App\Applications\Company\Exceptions\Employee\Verification;


use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EmailPinIncorrect extends AccessDeniedHttpException
{

    public function __construct()
    {
        parent::__construct(trans('exceptions.verification.code.incorrect'));
    }

}