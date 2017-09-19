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
 * Class RestorePasswordEmailVerificationFactory
 * @package App\Domains\Company\Mailables\Verification
 */
class RestorePasswordEmailVerificationFactory
{
    /**
     * @param string $verificationId
     * @param string $toEmail
     * @return EmailVerificationVerificationMethod
     */
    public function buildEmailVerificationMethod(
        string $verificationId,
        string $toEmail
    )
    {
        return EmailVerificationVerificationMethod::buildDefault(
            $toEmail,
            'Restore Password', // @TODO: trans
            'support@jincor.com',
            'Support Team', // @TODO: trans
            view('emails.restore-password.verify-email', [
                'email' => $toEmail,
                'verificationId' => $verificationId,
                'code' => '{{{CODE}}}',
            ])
        )->setPolicy('60 min')
            ->setForcedVerificationId(new VerificationIdentifier($verificationId))
            ->setGenerateCode(['DIGITS'], 6);
    }
}
