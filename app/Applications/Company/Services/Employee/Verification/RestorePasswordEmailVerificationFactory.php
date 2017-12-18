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
 * Class RestorePasswordEmailVerificationFactory
 * @package App\Domains\Company\Mailables\Verification
 */
class RestorePasswordEmailVerificationFactory
{
    /**
     * @param string $toEmail
     * @return EmailVerification
     * @internal param string $verificationId
     */
    public function buildEmailVerificationMethod(string $toEmail)
    {
        $template = view('emails.restore-password.verify-email', [
                'email' => $toEmail,
                'verificationId' => '{{{VERIFICATION_ID}}}',
                'code' => '{{{CODE}}}',
            ]);

        $emailVerification = new EmailVerification();
        return $emailVerification->setSubject('Restore Password') // @TODO: trans
            ->setFromName('Support Team') // @TODO: trans
            ->setFromEmail('support@jincor.com')
            ->setTemplate($template)
            ->setConsumer($toEmail)
            ->setExpiredOn('01:00:00')
            ->setGenerateCode([GenerateCode::DIGITS], 6);
    }
}
