<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 19.06.17
 * Time: 17:09
 */

namespace App\Applications\Company\Interfaces\Employee;

interface EmployeeVerificationServiceInterface
{
    public function sendEmailVerification(string $verificationId);

    public function sendEmailRestorePassword(string $email);

    public function verifyEmail(string $verificationId, string $pin);

    public function getRepository();
}
