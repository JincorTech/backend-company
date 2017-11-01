<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/1/17
 * Time: 2:01 PM
 */

namespace App\Applications\Company\Exceptions\Company;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EmployeeAlreadyExists extends HttpException
{
}