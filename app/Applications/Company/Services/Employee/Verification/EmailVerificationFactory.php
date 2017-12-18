<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by alekns <email: alexander.sedelnikov@gmail.com>
 * Date: 13.09.17
 * Time: 14:33
 */

namespace App\Applications\Company\Services\Employee\Verification;

use App\Core\Services\Verification\EmailVerificationVerificationMethod;
use App\Core\Services\Verification\VerificationIdentifier;
use JincorTech\VerifyClient\Interfaces\GenerateCode;
use JincorTech\VerifyClient\VerificationMethod\EmailVerification;

/**
 * Class EmailVerifyVerificationFactory
 * @package App\Domains\Employee\Mailables\Verification
 */
class EmailVerificationFactory
{
    /**
     * @param string $jwt
     * @param string $toEmail
     * @return EmailVerification
     */
    public function buildEmailVerificationMethod(string $jwt, string $toEmail)
    {
        $template = view('emails.registration.verify-email', [
            'jwt' => $jwt,
            'pin' => '{{{CODE}}}',
            'verificationId' => '{{{VERIFICATION_ID}}}'
        ]);

        $emailVerification = new EmailVerification();
        $emailVerification->setConsumer($toEmail)
            ->setGenerateCode([GenerateCode::DIGITS], 6)
            ->setExpiredOn('02:00:00')
            ->setPayload($jwt)
            ->setFromEmail('support@jincor.com')
            ->setFromName(trans('mails.verification.from'))
            ->setSubject(trans('mails.verification.subject'))
            ->setTemplate($template);

        return $emailVerification;
    }
}

