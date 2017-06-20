<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 19.06.17
 * Time: 17:09
 */

namespace App\Domains\Employee\Interfaces;
use App\Domains\Company\Entities\Company;

interface EmployeeVerificationServiceInterface
{
    public function beginVerificationProcess(Company $company);

    public function sendEmailVerification(string $verificationId, string $email);

    public function sendEmailRestorePassword(string $email);

    public function verifyEmail(string $verificationId, string $pin);

    public function getRepository();
}
