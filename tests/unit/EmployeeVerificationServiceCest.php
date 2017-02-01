<?php

use App\Domains\Employee\Repositories\EmployeeVerificationRepository;
use App\Domains\Employee\Services\EmployeeVerificationService;
use App\Domains\Employee\Exceptions\EmployeeNotFound;
use App\Domains\Employee\Entities\EmployeeVerification;
use Doctrine\ODM\MongoDB\DocumentManager;
use Faker\Factory;

class EmployeeVerificationServiceCest
{

    /**
     * @var \App\Domains\Employee\Services\EmployeeVerificationService
     */
    private $verificationService;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    private $dm;

    public function __construct()
    {
        $this->verificationService = new EmployeeVerificationService();
        $this->faker = Factory::create();
        $this->dm = App::make(DocumentManager::class);
    }


    public function beginVerificationProcess(UnitTester $I)
    {
        $company = CompanyFactory::make();
        $this->dm->persist($company);
        $verificationProcess = $this->verificationService->beginVerificationProcess($company);
        $I->assertEquals($company, $verificationProcess->getCompany());
        $I->assertFalse($verificationProcess->isEmailVerified());
        $I->assertNotNull($verificationProcess->getEmailCode());
        $I->assertNull($verificationProcess->getEmail());
    }

    public function sendEmailVerification(UnitTester $I)
    {
        $email = $this->faker->email;
        $verificationProcess = $this->makeVerification();
        $this->verificationService->sendEmailVerification($verificationProcess->getId(), $email);
        $I->assertEquals($email, $verificationProcess->getEmail());
        $I->assertFalse($verificationProcess->isEmailVerified());
        $I->assertNotNull($verificationProcess->getCompany());
    }

    public function sendEmailRestorePassword(UnitTester $I)
    {
        $I->expectException(EmployeeNotFound::class, function() {
            $this->verificationService->sendEmailRestorePassword($this->faker->email);
        });
        $employee = EmployeeFactory::make();
        $this->dm->persist($employee);
        $this->dm->flush($employee);
        $verification = $this->verificationService->sendEmailRestorePassword($employee->getContacts()->getEmail());
        $I->assertEquals($employee->getContacts()->getEmail(), $verification->getEmail());
        $I->assertNull($verification->getCompany());
    }

    public function getRepository(UnitTester $I)
    {
        $I->assertInstanceOf(EmployeeVerificationRepository::class, $this->verificationService->getRepository());
    }


    private function makeVerification() : EmployeeVerification
    {
        $company = CompanyFactory::make();
        $this->dm->persist($company);
        return $this->verificationService->beginVerificationProcess($company);
    }

}
