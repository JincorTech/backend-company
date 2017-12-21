<?php

use App\Applications\Company\Exceptions\Employee\InvitationLimitReached;
use App\Core\Interfaces\WalletsServiceInterface;
use App\Applications\Company\Services\Employee\EmployeeService;
use App\Core\Interfaces\EmployeeVerificationReason;
use App\Core\Services\IdentityService;
use App\Core\Services\JWTService;
use App\Core\Services\Exceptions\PasswordMismatchException;
use App\Applications\Company\Exceptions\Company\CompanyNotFound;
use App\Domains\Employee\Entities\Employee;
use App\Domains\Employee\Entities\EmployeeVerification;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Core\Interfaces\MessengerServiceInterface;
use Faker\Factory;
use Illuminate\Support\Collection;
use JincorTech\VerifyClient\Interfaces\VerifyService;
use JincorTech\VerifyClient\ValueObjects\EmailVerificationDetails;
use App\Applications\Company\Interfaces\Employee\EmployeeServiceInterface;

class EmployeeServiceCest
{
    private $employeePassword = 'Test2Test2Test';
    private $email = 'test2@test.com';

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    private $dm;

    /**
     * @var App\Applications\Company\Services\Employee\EmployeeService
     */
    private $employeeService;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * EmployeeServiceCest constructor.
     */
    public function __construct()
    {
        $this->dm = App::make(DocumentManager::class);
        $this->faker = Factory::create();
    }

    public function _before(UnitTester $I)
    {
        $messengerMock = Mockery::mock(MessengerServiceInterface::class);
        $messengerMock->shouldReceive('register')->once()->andReturn(true);
        App::instance(MessengerServiceInterface::class, $messengerMock);

        $identityMock = Mockery::mock(IdentityService::class);
        $identityMock->shouldReceive('register')->once()->andReturn(true);
        $identityMock->shouldReceive('login')->once()->andReturn('123');
        App::instance(IdentityService::class, $identityMock);

        $this->employeeService = $this->makeEmployeeService(['4fcad7c5-f84e-4d43-b35c-05e69d0e0364']);
    }

    /**
     * @param UnitTester $I
     *
     * Test if we can register new employees using EmployeeService
     */
    public function canRegisterEmployees(UnitTester $I)
    {
        $I->wantTo('Register user using EmployeeService');
        $email = $this->faker->email;
        $profile = EmployeeProfileFactory::make();
        $result = $this->employeeService->register('token', $email, $profile, $this->employeePassword);
        $employee = $result->getEmployee();
        $I->assertInstanceOf(Employee::class, $employee);
        $I->assertEquals($profile, $employee->getProfile());
        $I->assertEquals($employee->getContacts()->getEmail(), $email);
        $I->assertTrue($employee->isAdmin());
    }
    
    /**
     * Register employees with automatic addition of contacts
     *
     * @param UnitTester $I
     */
    public function canRegisterEmployeesWithAutomaticAdditionOfContacts(UnitTester $I)
    {
        $I->wantTo('Register employees with automatic addition of contacts');
        $token = '';
        $email1 = $this->faker->email;
        $profile = EmployeeProfileFactory::make();
        $employee1 = $this->employeeService->register($token, $email1, $profile, $this->employeePassword)->getEmployee();

        $I->assertCount(0, $employee1->getContactList());

        $employee1->activate();
        $this->dm->persist($employee1);
        $this->dm->flush();

        $email2 = $this->faker->email;
        $profile = EmployeeProfileFactory::make();
        $employee2 = $this->employeeService->register($token, $email2, $profile, $this->employeePassword)->getEmployee();
        $employee2->activate();
        $this->dm->persist($employee2);
        $this->dm->flush();

        $I->assertCount(1, $employee2->getContactList());
        $I->assertCount(1, $employee1->getContactList());

        $employee2->deactivate();
        $this->dm->persist($employee2);
        $this->dm->flush();

        $email3 = $this->faker->email;
        $profile = EmployeeProfileFactory::make();
        $employee3 = $this->employeeService->register($token, $email3, $profile, $this->employeePassword)->getEmployee();

        $I->assertCount(1, $employee3->getContactList());
        $I->assertCount(1, $employee2->getContactList());
        $I->assertCount(2, $employee1->getContactList());
    }

    /**
     * Create new employee and try to find it by email
     *
     * @param UnitTester $I
     */
    public function findByEmail(UnitTester $I)
    {
        $I->wantTo('Find existing user by email');
        $employee = $this->registerEmployee();
        $findResult = $this->employeeService->findByEmail($employee->getContacts()->getEmail());
        $I->assertEquals($employee, $findResult->first());
        $I->wantToTest('Passing wrong email leads to finding nothing');
        $I->assertEmpty($this->employeeService->findByEmail('1'.$employee->getContacts()->getEmail()));
    }

    /**
     * Create new employee and try to find it by passing login
     * (this also checks for login creation from the companyId and email of employee)
     *
     * @param UnitTester $I
     */
    public function findByLogin(UnitTester $I)
    {
        $I->wantTo('Find existing user by login');
        $employee = $this->registerEmployee();
        $findResult = $this->employeeService->findByLogin($employee->getLogin());
        $I->assertEquals($employee, $findResult);
        $I->wantToTest('Passing wrong login leads to finding nothing');
        $I->assertEmpty($this->employeeService->findByLogin('1'.$employee->getLogin()));
    }

    /**
     * Find employee by companyId and email
     *
     * @param UnitTester $I
     */
    public function findByCompanyIdAndEmail(UnitTester $I)
    {
        $I->wantTo('Find existing user by companyId and email');
        $employee = $this->registerEmployee();
        $this->dm->persist($employee->getCompany());
        $this->dm->flush($employee->getCompany());

        $employeeService = $this->employeeService;
        $findResult = $employeeService->findByCompanyIdAndEmail(
            $employee->getCompany()->getId(),
            $employee->getContacts()->getEmail()
        );
        $I->assertEquals($employee, $findResult);
        $I->wantToTest('Passing wrong company id leads to finding nothing as passing wrong email');
        $I->expectException(CompanyNotFound::class, function () use ($employee, $employeeService) {
            $employeeService->findByCompanyIdAndEmail(
                '1'.$employee->getCompany()->getId(),
                $employee->getContacts()->getEmail()
            );
        });
        $I->assertEmpty(
            $employeeService->findByCompanyIdAndEmail(
                $employee->getCompany()->getId(),
                '1'.$employee->getContacts()->getEmail()
            )
        );
    }

    /**
     * Find employees with matching email and password combination
     *
     * @param UnitTester $I
     */
    public function findByEmailAndPassword(UnitTester $I)
    {
        $employeeService = $this->makeEmployeeService([
            '514c0099-716f-4550-9d28-c25e2af9181e',
            '7026ba15-2d1d-4041-b6ed-536da0c1020a'
        ]);
        $email = $this->faker->email;
        $employee1 = $this->registerEmployeeWithSpecifyEmail($email, $employeeService);
        $employee2 = $this->registerEmployeeWithSpecifyEmail($email, $employeeService);

        $result = $employeeService->findByEmailAndPassword(
            $employee1->getContacts()->getEmail(),
            $this->employeePassword
        );
        $I->assertTrue(is_int($result->search($employee1)));
        $I->assertTrue(is_int($result->search($employee2)));
    }

    /**
     * Get companies by email and password
     *
     * @param UnitTester $I
     */
    public function getMatchingCompanies(UnitTester $I)
    {
        $employeeService = $this->makeEmployeeService([
            '514c0099-716f-4550-9d28-c25e2af9181e',
            '7026ba15-2d1d-4041-b6ed-536da0c1020a'
        ]);

        $employee1 = $this->registerEmployeeWithSpecifyEmail($this->email, $employeeService);
        $employee2 = $this->registerEmployeeWithSpecifyEmail($this->email, $employeeService);

        $this->dm->persist($employee1->getCompany());
        $this->dm->persist($employee2->getCompany());
        $companies = $employeeService->getMatchingCompanies([
            'email' => $this->email,
            'password' => $this->employeePassword
        ]);

        $I->assertNotEquals(false, $companies->search($employee1->getCompany()));
        $I->assertNotEquals(false, $companies->search($employee2->getCompany()));
    }

    /**
     * Test if we can get collection of all companies of the provided employees
     *
     * @param UnitTester $I
     */
    public function getEmployeesCompanies(UnitTester $I)
    {
        $employeeService = $this->makeEmployeeService([
            '514c0099-716f-4550-9d28-c25e2af9181e',
            '7026ba15-2d1d-4041-b6ed-536da0c1020a',
            '9fcad7c5-f84e-4d43-b35c-05e69d0e0362'
        ]);

        $employees = new Collection(
            [
                $this->registerEmployeeWithSpecifyEmail('emp1@test.com', $employeeService),
                $this->registerEmployeeWithSpecifyEmail('emp1@test.com', $employeeService),
                $this->registerEmployeeWithSpecifyEmail('emp1@test.com', $employeeService)
            ]
        );

        $companies = $employeeService->getEmployeesCompanies($employees);
        $I->assertEquals(3, $companies->count());
        $I->assertContains($employees->random()->getCompany(), $companies);
    }


    /**
     * Test change password functionality
     *
     * @param UnitTester $I
     */
    public function changePassword(UnitTester $I)
    {
        $I->wantTo("I want to change password of an Employee");
        $employee = $this->registerEmployee();
        $newPass = 'annsnd4848';
        $employeeService = $this->makeEmployeeService(['4fcad7c5-f84e-4d43-b35c-05e69d0e0364']);
        $I->expectException(PasswordMismatchException::class, function () use ($employee, $newPass, $employeeService) {
            $employeeService->changePassword($employee, $newPass, 'test' . $this->employeePassword);
        });
        $employeeService->changePassword($employee, $newPass, $this->employeePassword);
        $I->assertTrue($employee->checkPassword($newPass));
    }


    /**
     * Test can invite employee
     *
     * @param UnitTester $I
     */
    public function canInviteEmployee(UnitTester $I)
    {
        $email = $this->faker->email;
        $token = '';
        $profile = EmployeeProfileFactory::make();
        $employee = $this->employeeService->register($token, $email, $profile, $this->employeePassword)->getEmployee();


        Redis::shouldReceive('incr')->andReturnNull();
        Redis::shouldReceive('get')->with('4fcad7c5-f84e-4d43-b35c-05e69d0e0364:inv_emp@test.com')->andReturn(0);
        $verification = $this->employeeService->invite('inv_emp@test.com', $employee);
        $I->assertInstanceOf(EmployeeVerification::class, $verification);

        Redis::shouldReceive('get')->with('4fcad7c5-f84e-4d43-b35c-05e69d0e0364:inv_emp1@test.com')->andReturn(3);
        $I->expectException(new InvitationLimitReached(
            trans('exceptions.invitation.limitReached',
                ['email' => 'inv_emp1@test.com', 'limit' => config('mail.invitations.max_company_user')]
            )
        ), function () use ($employee) {
            $this->employeeService->invite('inv_emp1@test.com', $employee);
        });
    }



    /**
     * Register new Employee
     * @return Employee
     */
    private function registerEmployee()
    {
        $profile = EmployeeProfileFactory::make();

        return ($this->employeeService->register(
            'token',
            $this->faker->email,
            $profile,
            $this->employeePassword
        ))->getEmployee();
    }

    /**
     * Register new Employee
     * @param string $email
     * @param $employeeService
     * @return Employee
     * @internal param null $verification
     */
    private function registerEmployeeWithSpecifyEmail(string $email, $employeeService)
    {
        $profile = EmployeeProfileFactory::make();
        return ($employeeService->register('token', $email, $profile, $this->employeePassword))->getEmployee();
    }

    /**
     * @param array $companyIds List id of company
     * @param string $reason
     * @return EmployeeService
     */
    private function makeEmployeeService(array $companyIds, string $reason = EmployeeVerificationReason::REASON_REGISTER)
    {
        $jwtServiceMock = Mockery::mock(JWTService::class);
        $jwtServiceMock->shouldReceive('makeRegistrationToken')->once()->andReturn('token');
        $jwtServiceMock->shouldReceive('makeRegistrationCompanyToken')->once()->andReturn('tokenCompany');
        $jwtServiceMock->shouldReceive('getCompanyId')->andReturnValues($companyIds);
        $returnData = [];
        foreach ($companyIds as $companyId) {
            $returnData[] = ['reason' => $reason, 'companyId' => $companyId];
        }
        $jwtServiceMock->shouldReceive('getData')->andReturnValues($returnData);
        App::instance(JWTService::class, $jwtServiceMock);

        $verifyMock = Mockery::mock(VerifyService::class);
        $verifyMock->shouldReceive('initiate')->once()->andReturn(
            new EmailVerificationDetails([
                'status' => '200',
                'verificationId' => 'd3d548c5-2c7d-4ae0-9271-8e41b7f03714',
                'expiredOn' => 12345678,
                'consumer' => 'ivan@wizard.com'
            ])
        );
        App::instance(VerifyService::class, $verifyMock);

        $walletsMock = Mockery::mock(WalletsServiceInterface::class);
        $walletsMock->shouldReceive('register')->andReturnNull();
        $walletsMock->shouldReceive('registerCorporate')->andReturnNull();

        App::instance(WalletsServiceInterface::class, $walletsMock);

        return App::make(EmployeeServiceInterface::class);
    }
}
