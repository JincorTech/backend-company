<?php

namespace App\Core\Interfaces;


interface EmployeeVerificationReason
{
    const REASON_REGISTER = 'register';
    const REASON_RESTORE = 'restore';
    const REASON_INVITED_BY_EMPLOYEE = 'invited-by-employee';
}