<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/16/17
 * Time: 6:36 PM
 */

namespace App\Domains\Company\Services;

use App\Domains\Company\Entities\EmployeeVerification;
use App\Domains\Company\Exceptions\ContactsNotVerified;
use App\Domains\Company\ValueObjects\EmployeeProfile;
use App\Domains\Company\Entities\Employee;
use App\Domains\Company\Events\EmployeeRegisteredEvent;
use Doctrine\ODM\MongoDB\DocumentManager;
use App;
use App\Domains\Company\Exceptions\EmployeeAlreadyExists;

class EmployeeRegistrationService
{
    private $verificationRepository;

    private $employeeService;

    public function __construct()
    {
        $this->dm = App::make(DocumentManager::class);
        $this->verificationRepository = $this->dm->getRepository(EmployeeVerification::class);
        $this->employeeService = new EmployeeService();
    }

    public function register(
        string $verificationId,
        EmployeeProfile $profile,
        string $password
    ) {
        /** @var EmployeeVerification $verification */
        $verification = $this->verificationRepository->find($verificationId);
        if (!$verification || !$verification->completelyVerified()) {
            throw new ContactsNotVerified('You must verify email and phone before registration');
        }
        $company = $verification->getCompany();
        $scope = 'company-employee';
        if ($company->getEmployees()->count() === 0) {
            $scope = 'company-admin';
        }
        if ($this->employeeService->findByCompanyIdAndEmail($company->getId(), $verification->getEmail())) {
            throw new EmployeeAlreadyExists(
                'Employee '.$verification->getEmail().' already exists in company '.
                $company->getLegalName()
            );
        }
        //TODO: check if employee with this email\phone already exists
        $profile->setLogin($company, $verification->getEmail());
        $employee = Employee::register($verification, $profile, $password);
        $company->addEmployee($employee);
        $this->dm->persist($employee);
        $this->dm->persist($company);
        $this->dm->flush();

        event(new EmployeeRegisteredEvent($company, $employee, $scope));

        return $employee;
    }
}
