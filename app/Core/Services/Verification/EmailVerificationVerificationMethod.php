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
 * Class EmailVerificationVerificationMethod
 * @package App\Core\Services\Verification
 */
class EmailVerificationVerificationMethod implements VerificationMethod
{
    const METHOD_TYPE = 'email';

    use BaseVerificationMethodTrait;

    /**
     * @inheritdoc
     */
    public function getMethodType(): string
    {
        return self::METHOD_TYPE;
    }

    /**
     * Set up default email verification method.
     * @param string $toEmail
     * @param string $subject
     * @param string $fromEmail
     * @param string $fromName
     * @param string $templateContent
     * @return EmailVerificationVerificationMethod
     */
    public static function buildDefault(string $toEmail, string $subject, string $fromEmail, string $fromName, string $templateContent)
    {
        $instance = (new static())->setTemplate($templateContent)
            ->setConsumer($toEmail);

        $instance->template += [
            'fromEmail' => $fromEmail,
            'fromName' => $fromName,
            'subject' => $subject
        ];

        return $instance;
    }
}
