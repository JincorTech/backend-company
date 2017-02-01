<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/16/17
 * Time: 6:39 PM
 */

namespace App\Domains\Company\Services;

use App\Domains\Company\Entities\EmployeeVerification;
use App\Domains\Company\Entities\Company;
use App\Domains\Company\Repositories\EmployeeVerificationRepository;
use App\Domains\Company\Mailables\VerifyEmail;
use Doctrine\ODM\MongoDB\DocumentManager;
use Mail;
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
     * @return EmployeeVerification
     */
    public function beginVerificationProcess(Company $company)
    {
        $verification = new EmployeeVerification($company);
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
     * @return EmployeeVerification
     */
    public function sendEmailVerification(string $verificationId, string $email)
    {
        /** @var EmployeeVerification $verification */
        $verification = $this->verificationRepository->find($verificationId);
        $verification->associateEmail($email);
        $this->dm->persist($verification);
        $this->dm->flush($verification);
        Mail::to($email)->queue(new VerifyEmail($verification->getEmailCode()));

        return $verification;
    }

    /**
     * Verify email address by pin code provided and employee instance.
     *
     * @param string $verificationId
     * @param string $pin
     *
     * @return EmployeeVerification
     */
    public function verifyEmail(string $verificationId, string $pin)
    {
        /** @var EmployeeVerification $verification */
        $verification = $this->verificationRepository->find($verificationId);
        $verification->verifyEmail($pin);
        $this->dm->persist($verification);
        $this->dm->flush($verification);

        return $verification;
    }

    /**
     * Send verification code to provided phone number.
     *
     * @param string $verificationId
     * @param string $phone
     *
     * @return EmployeeVerification
     */
    public function sendPhoneVerification(string $verificationId, string $phone)
    {
        $verification = $this->verificationRepository->find($verificationId);
        $verification->associatePhone('');
        //TODO: implement sending of phone verification
        $this->dm->persist($verification);
        $this->dm->flush($verification);

        return $verification;
    }

    /**
     * @param string $verificationId
     * @param string $pin
     *
     * @return EmployeeVerification
     */
    public function verifyPhone(string $verificationId, string $pin)
    {
        $verification = $this->verificationRepository->find($verificationId);
        $verification->verifyPhone('');
        $this->dm->persist($verification);
        $this->dm->flush($verification);

        return $verification;
    }
}
