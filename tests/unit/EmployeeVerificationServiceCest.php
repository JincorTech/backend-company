<?php

use App\Core\Repositories\EmployeeVerificationRepository;
use App\Applications\Company\Interfaces\Employee\EmployeeVerificationServiceInterface;
use App\Applications\Company\Exceptions\Employee\EmployeeNotFound;
use App\Domains\Employee\Entities\EmployeeVerification;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Core\Interfaces\IdentityInterface;
use Faker\Factory;
use JincorTech\VerifyClient\Interfaces\VerifyService;
use JincorTech\VerifyClient\ValueObjects\EmailVerificationDetails;

class EmployeeVerificationServiceCest
{

    /**
     * @var \App\Applications\Company\Services\Employee\EmployeeVerificationService
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
        $this->verificationService = App::make(EmployeeVerificationServiceInterface::class);
        $this->faker = Factory::create();
        $this->dm = App::make(DocumentManager::class);
    }

    public function _before(UnitTester $I)
    {
        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')->once()->andReturn(true);
        App::instance(IdentityInterface::class, $identityMock);
    }


    public function beginVerificationProcess(UnitTester $I)
    {
        $employee = EmployeeFactory::make();
        $verifyMock = Mockery::mock(VerifyService::class);
        $verifyMock->shouldReceive('initiate')->once()->andReturn(
            new EmailVerificationDetails([
                'status' => '200',
                'verificationId' => '6b90fa0c-7912-452c-bddf-e2c718440251',
                'expiredOn' => 12345678,
                'consumer' => $employee->getContacts()->getEmail(),
            ])
        );
        App::instance(VerifyService::class, $verifyMock);
        $this->verificationService = App::make(EmployeeVerificationServiceInterface::class);

        $this->dm->persist($employee);
        $this->dm->persist($employee->getCompany());

        // @TODO: add test
    }

    public function sendEmailVerification(UnitTester $I)
    {
        $employee = EmployeeFactory::make();
        $verifyMock = Mockery::mock(VerifyService::class);
        $verifyMock->shouldReceive('initiate')->once()->andReturn(
            new EmailVerificationDetails([
                'status' => '200',
                'verificationId' => '6b90fa0c-7912-452c-bddf-e2c718440251',
                'expiredOn' => 12345678,
                'consumer' => $employee->getContacts()->getEmail(),
            ])
        );
        App::instance(VerifyService::class, $verifyMock);
        $this->verificationService = App::make(EmployeeVerificationServiceInterface::class);

        $email = $employee->getContacts()->getEmail();
        // @TODO: add test
    }

    public function sendEmailRestorePassword(UnitTester $I)
    {
        $verifyMock = Mockery::mock(VerifyService::class);
        $verifyMock->shouldReceive('initiate')->andReturn(
            new EmailVerificationDetails([
                'status' => '200',
                'verificationId' => 'd3d548c5-2c7d-4ae0-9271-8e41b7f03714',
                'expiredOn' => 12345678,
                'consumer' => 'email'
            ])
        );
        $I->haveInstance(VerifyService::class, $verifyMock);
        
        $I->expectException(EmployeeNotFound::class, function () {
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

}
