<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by alekns <email: alexander.sedelnikov@gmail.com>
 * Date: 13.09.17
 * Time: 13:43
 */

namespace App\Core\Services\Verification;

/**
 * Interface VerificationMethod
 * @package App\Core\Services\Verification
 */
interface VerificationMethod
{
    /**
     * @return string
     */
    public function getMethodType(): string;

    /**
     * @internal
     * Make an array.
     * @return array
     */
    public function getRequestParameters(): array;
}
