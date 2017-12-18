<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by alekns <email: alexander.sedelnikov@gmail.com>
 * Date: 13.09.17
 * Time: 14:33
 */

namespace App\Applications\Company\Services\Employee\Verification;


use JincorTech\VerifyClient\Interfaces\GenerateCode;
use JincorTech\VerifyClient\VerificationMethod\EmailVerification;

/**
 * Class InviteEmailVerificationFactory
 * @package App\Applications\Company\Services\Employee\Verification
 */
class InviteEmailVerificationFactory
{
    /**
     * @param string $jwt
     * @param string $companyName
     * @param string $employee
     * @param string $toEmail
     * @return EmailVerification
     * @internal param string $verificationId
     * @internal param string $verificationId
     */
    public function buildEmailVerificationMethod(
        string $jwt,
        string $companyName,
        string $employee,
        string $toEmail
    )
    {
        $template = view('emails.invitations.invite', [
                'email' => $toEmail,
                'employee' => $employee,
                'jwt' => $jwt,
                'verificationId' => '{{{VERIFICATION_ID}}}',
                'companyName' => $companyName
            ]);

        $verificationEmail = new EmailVerification();
        return $verificationEmail->setSubject('You are Invited') // @TODO: trans
            ->setFromEmail('support@jincor.com')
            ->setFromName('Support Team') // @TODO: trans
            ->setTemplate($template)
            ->setGenerateCode([GenerateCode::DIGITS], 6)
            ->setExpiredOn('23:59:59')
            ->setConsumer($toEmail)
            ->setPayload($jwt);
    }
}