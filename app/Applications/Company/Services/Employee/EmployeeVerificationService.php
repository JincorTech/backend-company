<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/16/17
 * Time: 6:39 PM
 */

namespace App\Applications\Company\Services\Employee;

use App\Applications\Company\Services\Employee\Verification\EmailVerificationFactory;
use App\Applications\Company\Services\Employee\Verification\RestorePasswordEmailVerificationFactory;
use App\Core\Interfaces\EmployeeVerificationReason;
use App\Core\Services\JWTService;
use App\Domains\Employee\Interfaces\EmployeeVerificationRepositoryInterface;
use App\Core\Services\Verification\Exceptions\EmployeeVerificationNotFound;
use App\Applications\Company\Interfaces\Employee\EmployeeVerificationServiceInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Employee\Entities\Employee;
use App\Domains\Employee\Entities\EmployeeVerification;
use App\Applications\Company\Exceptions\Employee\EmployeeNotFound;
use App;
use Doctrine\ODM\MongoDB\DocumentRepository;
use JincorTech\VerifyClient\Interfaces\VerifyService;
use JincorTech\VerifyClient\ValueObjects\EmailValidationData;
use JincorTech\VerifyClient\ValueObjects\Uuid;

class EmployeeVerificationService implements EmployeeVerificationServiceInterface
{
    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var EmployeeVerificationRepositoryInterface | DocumentRepository
     */
    private $verificationRepository;

    /**
     * @var JWTService
     */
    private $jwtService;

    /**
     * @var VerifyService
     */
    private $verifyService;

    /**
     * EmployeeVerificationService constructor.
     * @param EmployeeVerificationRepositoryInterface $verificationRepository
     * @param JWTService $jwtService
     * @param VerifyService $verifyService
     */
    public function __construct(
        EmployeeVerificationRepositoryInterface $verificationRepository,
        JWTService $jwtService,
        VerifyService $verifyService
    )
    {
        $this->dm = App::make(DocumentManager::class);
        $this->verificationRepository = $verificationRepository;
        $this->jwtService = $jwtService;
        $this->verifyService = $verifyService;
    }

    /**
     * Send verification code to email address.
     *
     * @param string $verificationId
     * @throws \App\Core\Services\Verification\Exceptions\EmployeeVerificationNotFound
     * @return EmployeeVerification
     */
    public function sendEmailVerification(string $verificationId)
    {
        /** @var \App\Domains\Employee\Entities\EmployeeVerification $verification */
        $verification = $this->verificationRepository->find($verificationId);
        if (!$verification) {
            throw new EmployeeVerificationNotFound(trans('exceptions.employee.verification.not_found', [
                'verification' => $verificationId,
            ]));
        }

        $token = $this->jwtService->makeRegistrationToken(
            $verification->getEmail(),
            $verification->getCompany()->getProfile()->getName(),
            $verification->getCompany()->getId(),
            $verification->getReason()
        );

        $emailVerification = (new EmailVerificationFactory())->buildEmailVerificationMethod(
            $token,
            $verification->getEmail()
        );

        $verificationDetails = $this->verifyService->initiate($emailVerification);

        $verification = clone $verification;
        $verification->setId($verificationDetails->getVerificationId());
        $this->dm->persist($verification);
        $this->dm->flush();

        return $verification;
    }

    // @TODO: It is necessary to make a working variant
    public function sendEmailRestorePassword(string $email)
    {
        /** @var Employee $existing */
        $existing = $this->dm->getRepository(Employee::class)->findBy(['contacts.email' => $email]);
        if (!$existing) {
            throw new EmployeeNotFound(trans('exceptions.restore-password.notFound', ['email' => $email]));
        }

        $verificationEmail = (new RestorePasswordEmailVerificationFactory())->buildEmailVerificationMethod($email);

        $verificationDetails = $this->verifyService->initiate($verificationEmail);

        $verification = new EmployeeVerification(EmployeeVerificationReason::REASON_RESTORE);
        $verification->setId($verificationDetails->getVerificationId());
        $verification->associateEmail($email);
        $this->dm->persist($verification);
        $this->dm->flush();

        return $verification;
    }

    /**
     * Verify email address by pin code provided and employee instance.
     *
     * @param string $verificationId
     * @param string $pin
     *
     * @return EmployeeVerification
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public function verifyEmail(string $verificationId, string $pin)
    {
        $verificationResult = $this->verifyService->validate(new EmailValidationData(new Uuid($verificationId), $pin));

        /** @var EmployeeVerification $verification */
        $verification = $this->verificationRepository->find($verificationResult->getVerificationId());
        $verification->setVerifyEmail(true);
        $this->dm->persist($verification);
        $this->dm->flush();

        return $verification;
    }

    public function getRepository()
    {
        return $this->verificationRepository;
    }

}
