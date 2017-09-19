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
 * Class VerificationAccess
 * @package App\Core\Services\Verification
 */
abstract class VerificationData
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var VerificationIdentifier
     */
    private $verificationIdentifier;

    /**
     * VerificationAccess constructor.
     * @param VerificationIdentifier $verificationIdentifier
     * @param string $code
     */
    public function __construct(VerificationIdentifier $verificationIdentifier, string $code)
    {
        $this->verificationIdentifier = $verificationIdentifier;
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return VerificationIdentifier
     */
    public function getVerificationIdentifier(): VerificationIdentifier
    {
        return $this->verificationIdentifier;
    }

    /**
     * @internal
     * @return array
     */
    public function getFormattedApiRequestParameters(): array
    {
        return [
            'code' => $this->getCode()
        ];
    }

    /**
     * Get method type.
     * @return string
     */
    public abstract function getMethodType(): string;
}
