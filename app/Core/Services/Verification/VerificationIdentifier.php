<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by alekns <email: alexander.sedelnikov@gmail.com>
 * Date: 13.09.17
 * Time: 13:37
 */

namespace App\Core\Services\Verification;

/**
 * Class VerificationIdentifier
 * @package App\Core\Services\Verification
 */
class VerificationIdentifier
{
    const VALID_UUID_FORMAT_REGEX = '/^[\da-f]{8}-([\da-f]{4}-){3}[\da-f]{12}|[\d]{32}$/';

    /**
     * @var string
     */
    protected $verificationId;

    /**
     * @var integer|null
     */
    protected $expiredOn;

    /**
     * Identifier constructor.
     * @param string $verificationId
     * @throws \InvalidArgumentException
     */
    public function __construct(string $verificationId)
    {
        if (empty($verificationId) || !preg_match(
                VerificationIdentifier::VALID_UUID_FORMAT_REGEX,
                $verificationId
            )) {
            throw new \InvalidArgumentException('Verification Id is not well uuid formatted');
        }
        $this->verificationId = $verificationId;
    }

    /**
     * @return string
     */
    public function getVerificationId(): string
    {
        return $this->verificationId;
    }

    /**
     * @return int|null
     */
    public function getExpiredOn()
    {
        return $this->expiredOn;
    }

    /**
     * @param int $expiredOn
     * @return self
     */
    public function setExpiredOn(int $expiredOn): self
    {
        $this->expiredOn = $expiredOn;
        return $this;
    }
}
