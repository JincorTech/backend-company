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

/**
 * Class EmailVerifyVerificationFactory
 * @package App\Domains\Employee\Mailables\Verification
 */
class EmailVerificationFactory
{
    /**
     * @param string $jwt
     * @param string $verificationId
     * @param string $toEmail
     * @return EmailVerificationVerificationMethod
     */
    public function buildEmailVerificationMethod(
        string $jwt,
        string $verificationId,
        string $toEmail
    )
    {
        return EmailVerificationVerificationMethod::buildDefault(
            $toEmail,
            trans('mails.verification.subject'),
            'support@jincor.com',
            trans(trans('mails.verification.from')),
            view('emails.registration.verify-email', [
                'jwt' => $jwt,
                'pin' => '{{{CODE}}}',
            ])
        )->setPolicy('02:00:00')
            ->setForcedVerificationId(new VerificationIdentifier($verificationId))
            ->setGenerateCode(['DIGITS'], 6);
    }
}

