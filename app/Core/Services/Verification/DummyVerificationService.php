<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by alekns <email: alexander.sedelnikov@gmail.com>
 * Date: 13.09.17
 * Time: 14:33
 */

namespace App\Core\Services\Verification;
use App\Domains\Employee\Entities\EmployeeVerification;
use App\Domains\Employee\Exceptions\EmployeeVerificationNotFound;
use App\Domains\Employee\Interfaces\EmployeeVerificationRepositoryInterface;


/**
 * Class FileBaseVerificationService
 * @package App\Core\Services\Verification
 */
class DummyVerificationService implements VerificationService
{
    /**
     * @var EmployeeVerificationRepositoryInterface
     */
    private $verificationRepository;

    /**
     * FileBaseVerificationService constructor.
     * @param EmployeeVerificationRepositoryInterface $verificationRepository
     */
    public function __construct(EmployeeVerificationRepositoryInterface $verificationRepository)
    {
        $this->verificationRepository = $verificationRepository;
    }

    private function getEmployeeVerification(string $verificationId): EmployeeVerification
    {
        /** @var EmployeeVerification $verification */
        $verification = $this->verificationRepository->find($verificationId);
        if (!$verification || $verification->isEmailVerified()) {
            throw new EmployeeVerificationNotFound(trans('exceptions.employee.verification.not_found', [
                'verification' => $verificationId,
            ]));
        }
        return $verification;
    }

    /**
     * @inheritdoc
     */
    public function initiate(VerificationMethod $method): VerificationIdentifier
    {
        $parameters = $method->getRequestParameters();

        $verificationId = $parameters['policy']['forcedVerificationId'] ?? null;

        $employeeVerification = $this->getEmployeeVerification($verificationId);

        return (new VerificationIdentifier($verificationId))
            ->setExpiredOn(time() + 3600);
    }

    /**
     * @inheritdoc
     */
    public function validate(VerificationData $data): bool
    {
        $employeeVerification = $this->getEmployeeVerification($data->getVerificationIdentifier()->getVerificationId());

        return $employeeVerification->getEmailCode() == $data->getCode();
    }
}
