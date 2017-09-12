<?php

namespace App\Applications\Company\Http\Requests\Employee;

interface PasswordValidation
{
    const PASSWORD_REGEX = 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z0\d!"#$%\\\&\'\(\)\*\+,\-.\/:;<=>\?@\[\]^_`{|}\~]{8,}$/';
}
