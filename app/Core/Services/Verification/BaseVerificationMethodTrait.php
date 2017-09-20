<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by alekns <email: alexander.sedelnikov@gmail.com>
 * Date: 13.09.17
 * Time: 13:15
 */

namespace App\Core\Services\Verification;

/**
 * Trait BaseVerificationMethodTrait
 * @package App\Core\Services\Verification
 * Common methods.
 */
trait BaseVerificationMethodTrait
{
    /**
     * Consumer for verification code.
     * @var string
     */
    protected $consumer;

    /**
     * Message template.
     * @var array[string]
     */
    protected $template;

    /**
     * Verification code parameters.
     * @var array[string]
     */
    protected $generateCode;

    /**
     * Verification code policy.
     * @var
     */
    protected $policy = [];

    /**
     * Force own verification id.
     * @var VerificationIdentifier
     */
    protected $forcedVerificationId;

    /**
     * Force own code.
     * @var string
     */
    protected $forcedCode;

    /**
     * Set consumer for verification code.
     *
     * @param   string  $consumer
     * @return  self
     */
    public function setConsumer(string $consumer): self
    {
        $this->consumer = $consumer;
        return $this;
    }

    /**
     * Set message template.
     *
     * @param   string  $template
     * @return  self
     * @throws  \InvalidArgumentException
     */
    public function setTemplate(string $template): self
    {
        if (empty($template)) {
            throw new \InvalidArgumentException('Template is empty');
        }

        $this->template = [
            'body' => $template
        ];
        return $this;
    }

    /**
     * Set to force usage of own passed verification id (uuid formatted).
     *
     * @param $verificationId VerificationIdentifier
     * @return self
     */
    public function setForcedVerificationId(VerificationIdentifier $verificationId): self
    {
        $this->forcedVerificationId = $verificationId;
        return $this;
    }

    /**
     * @param string $forcedCode
     * @return  self
     * @throws  \InvalidArgumentException
     */
    public function setForcedCode(string $forcedCode)
    {
        if (empty($forcedCode)) {
            throw new \InvalidArgumentException('Too short length');
        }

        $this->forcedCode = $forcedCode;

        return $this;
    }

    /**
     * Set generated code parameters.
     *
     * @param   array $symbolsSet  Allowed symbols in verification code
     * @param   int   $length   Length of code
     * @return  self
     * @throws  \InvalidArgumentException
     */
    public function setGenerateCode(array $symbolsSet, int $length): self
    {
        if ($length < 2) {
            throw new \InvalidArgumentException('Too short length');
        }

        foreach ($symbolsSet as $set) {
            if (!is_string($set) || empty($set)) {
                throw new \InvalidArgumentException('Invalid symbol set');
            }
        }

        $this->generateCode = [
            'symbolSet' => $symbolsSet,
            'length' => $length
        ];

        return $this;
    }

    /**
     * Set verification code policy.
     *
     * @param   string  $expiredOn
     * @return  self
     */
    public function setPolicy(string $expiredOn): self
    {
        if (empty($expiredOn)) {
            throw new \InvalidArgumentException('Expired on is empty');
        }

        $this->policy = [
            'expiredOn' => $expiredOn
        ];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRequestParameters(): array
    {
        $parameters = [
            'consumer' => $this->consumer,
            'template' => $this->template,
            'policy' => $this->policy
        ];

        if ($this->generateCode) {
            $parameters['generateCode'] = $this->generateCode;
        }

        if ($this->forcedVerificationId) {
            $parameters['policy']['forcedVerificationId'] = $this->forcedVerificationId->getVerificationId();
        }

        if ($this->forcedCode) {
            $parameters['policy']['forcedCode'] = $this->forcedCode;
        }

        return $parameters;
    }
}
