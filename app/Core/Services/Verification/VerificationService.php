<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by alekns <email: alexander.sedelnikov@gmail.com>
 * Date: 13.09.17
 * Time: 13:31
 */

namespace App\Core\Services\Verification;

/**
 * Interface VerificationService
 * @package App\Core\Services\Verification
 *
 * Interface for verifications.
 */
interface VerificationService
{
    /**
     * @param VerificationMethod $method
     * @return VerificationIdentifier
     */
    public function initiate(VerificationMethod $method): VerificationIdentifier;

    /**
     * @param VerificationData $data
     * @return bool
     */
    public function validate(VerificationData $data): bool;
}
