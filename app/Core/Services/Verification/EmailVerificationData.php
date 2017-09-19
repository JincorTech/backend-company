<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by alekns <email: alexander.sedelnikov@gmail.com>
 * Date: 13.09.17
 * Time: 15:42
 */

namespace App\Core\Services\Verification;

/**
 * Class EmailVerificationData
 * @package App\Core\Services\Verification
 */
class EmailVerificationData extends VerificationData
{
    /**
     * @inheritdoc
     */
    public function getMethodType(): string
    {
        return EmailVerificationVerificationMethod::METHOD_TYPE;
    }

    /**
     * Define default verification data.
     *
     * @param string $verificationId
     * @param string $code
     * @return EmailVerificationData
     */
    public static function buildDefault(string $verificationId, string $code): self
    {
        return new static(new VerificationIdentifier($verificationId), $code);
    }
}
