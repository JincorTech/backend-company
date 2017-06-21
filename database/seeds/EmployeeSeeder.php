<?php


use App\Domains\Employee\Services\EmployeeVerificationService;
use App\Domains\Employee\Entities\EmployeeVerification;
use App\Domains\Employee\ValueObjects\EmployeeProfile;
use App\Domains\Employee\Interfaces\EmployeeVerificationServiceInterface;
use App\Domains\Employee\Interfaces\EmployeeServiceInterface;
use App\Domains\Company\Entities\Company;

/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 04/04/2017
 * Time: 13:09
 */
class EmployeeSeeder extends DatabaseSeeder
{

    /**
     *
     */
    public function run()
    {
        /** @var Company $company */
        $company = $this->getDm()->getRepository(Company::class)->findOneBy([
            'profile.legalName' => env('TEST_COMPANY_NAME'),
        ]);
        /** @var Company $company2 */
        $company2 = $this->getDm()->getRepository(Company::class)->findOneBy([
            'profile.legalName' => 'Jincor Limited',
        ]);
        $this->registerEmployee($company);
        $this->registerEmployee($company2);
    }

    private function registerEmployee(Company $company)
    {
        $verification = $this->getEmployeeVerificationService()->beginVerificationProcess($company);
        $verification->associateEmail('test@test.com');
        $verification->verifyEmail($verification->getEmailCode());
        $verification->associateCompany($company);
        $this->getDm()->persist($verification);
        $profile = new EmployeeProfile('John', 'Doe', 'Tester');
        $this->getEmployeeService()->register($verification->getId(), $profile, 'test');
    }

    /**
     * @return EmployeeVerificationServiceInterface
     */
    private function getEmployeeVerificationService() : EmployeeVerificationServiceInterface
    {
        return $this->container->make(EmployeeVerificationServiceInterface::class);
    }

    /**
     * @return EmployeeServiceInterface
     */
    private function getEmployeeService() : EmployeeServiceInterface
    {
        return $this->container->make(EmployeeServiceInterface::class);
    }



}