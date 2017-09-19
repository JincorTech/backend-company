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
     * @param string $verificationId
     * @return EmailVerificationVerificationMethod
     * @internal param string $verificationId
     */
    public function buildEmailVerificationMethod(
        string $jwt,
        string $companyName,
        string $employee,
        string $toEmail,
        string $verificationId
    )
    {
        return EmailVerificationVerificationMethod::buildDefault(
            $toEmail,
            'You are Invited',  // @TODO: trans
            'support@jincor.com',
            'Support Team',  // @TODO: trans
            view('emails.invitations.invite', [
                'email' => $toEmail,
                'employee' => $employee,
                'jwt' => $jwt,
            ])
        )->setPolicy('1 day')
            ->setForcedVerificationId(new VerificationIdentifier($verificationId));
    }
}