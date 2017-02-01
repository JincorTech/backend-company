<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/16/17
 * Time: 6:39 PM
 */

namespace App\Domains\Employee\Services;

use App\Domains\Employee\EntityDecorators\RestorePasswordVerification;
use App\Domains\Employee\Repositories\EmployeeVerificationRepository;
use App\Domains\Employee\EntityDecorators\RegistrationVerification;
use App\Domains\Employee\Exceptions\EmployeeVerificationNotFound;
use App\Domains\Employee\Events\VerificationEmailRequested;
use App\Domains\Employee\Events\RestorePasswordRequested;
use App\Domains\Employee\Entities\EmployeeVerification;
use App\Domains\Company\Entities\Company;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Employee\Entities\Employee;
use App\Domains\Employee\Exceptions\EmployeeNotFound;
use App;

class EmployeeVerificationService
{
    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var EmployeeVerificationRepository
     */
    private $verificationRepository;

    public function __construct()
    {
        $this->dm = App::make(DocumentManager::class);
        $this->verificationRepository = $this->dm->getRepository(EmployeeVerification::class);
    }

    /**
     * @param Company $company
     * @return \App\Domains\Employee\Entities\EmployeeVerification
     */
    public function beginVerificationProcess(Company $company)
    {
        $verification = RegistrationVerification::make($company);
        $this->dm->persist($verification);
        $this->dm->flush($verification);
        return $verification;
    }

    /**
     * Send verification code to email address.
     *
     * @param string $verificationId
     * @param string $email
     *
     * @return \App\Domains\Employee\Entities\EmployeeVerification
     */
    public function sendEmailVerification(string $verificationId, string $email)
    {
        /** @var \App\Domains\Employee\Entities\EmployeeVerification $verification */
        $verification = $this->verificationRepository->find($verificationId);
        $verification->associateEmail($email);
        event(new VerificationEmailRequested($verification));
        $this->dm->persist($verification);
        $this->dm->flush($verification);
        return $verification;
    }


    public function sendEmailRestorePassword(string $email)
    {
        /** @var Employee $existing */
        $existing = $this->dm->getRepository(Employee::class)->findBy(['contacts.email' => $email]);
        if(!$existing) {
            throw new EmployeeNotFound(trans('exceptions.restore-password.notFound', ['email' => $email]));
        }
        $verification = RestorePasswordVerification::make($email);
        $this->dm->persist($verification->getVerification());
        $this->dm->flush($verification->getVerification());
        event(new RestorePasswordRequested($verification->getVerification()));
        return $verification;
    }

    /**
     * Verify email address by pin code provided and employee instance.
     *
     * @param string $verificationId
     * @param string $pin
     *
     * @return \App\Domains\Employee\Entities\EmployeeVerification
     */
    public function verifyEmail(string $verificationId, string $pin)
    {
        /** @var \App\Domains\Employee\Entities\EmployeeVerification $verification */
        $verification = $this->verificationRepository->find($verificationId);
        if (!$verification) {
            throw new EmployeeVerificationNotFound("Employee verification " . $verificationId . 'cannot be found on the server');
        }
        $verification->verifyEmail($pin);
        $this->dm->persist($verification);
        $this->dm->flush($verification);
        return $verification;
    }

    public function getRepository()
    {
        return $this->verificationRepository;
    }
}
