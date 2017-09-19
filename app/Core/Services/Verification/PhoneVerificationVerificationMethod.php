<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by alekns <email: alexander.sedelnikov@gmail.com>
 * Date: 13.09.17
 * Time: 16:43
 */

namespace App\Core\Services\Verification;

/**
 * Class EmailVerificationVerificationMethod
 * @package App\Core\Services\Verification
 */
class PhoneVerificationVerificationMethod implements VerificationMethod
{
    const METHOD_TYPE = 'phone';

    use BaseVerificationMethodTrait;

    /**
     * @return string
     */
    public function getMethodType(): string
    {
        return self::METHOD_TYPE;
    }
}
