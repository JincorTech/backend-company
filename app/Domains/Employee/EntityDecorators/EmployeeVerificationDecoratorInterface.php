<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/27/17
 * Time: 12:18 AM
 */

namespace App\Domains\Employee\EntityDecorators;


use App\Domains\Employee\Entities\EmployeeVerification;

interface EmployeeVerificationDecoratorInterface
{

    public function __construct(EmployeeVerification $verification);

    public function getVerification(): EmployeeVerification;

    public function completelyVerified() : bool;

}