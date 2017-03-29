<?php

use App\Domains\Employee\EntityDecorators\RegistrationVerification;
use App\Domains\Employee\EntityDecorators\RestorePasswordVerification;
use App\Domains\Employee\Exceptions\EmployeeVerificationNotFound;
use App\Domains\Employee\Exceptions\PasswordMismatchException;
use App\Domains\Employee\Services\EmployeeVerificationService;
use App\Domains\Employee\Exceptions\CompanyNotFound;
use App\Domains\Employee\Services\EmployeeService;
use App\Domains\Employee\Entities\Employee;
use Doctrine\ODM\MongoDB\DocumentManager;
use Faker\Factory;
use Illuminate\Support\Collection;

class EmployeeServiceCest
{
    private $employeePassword = 'Test2Test2Test';
    private $email = 'test2@test.com';

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    private $dm;

    /**
     * @var EmployeeVerificationService
     */
    private $verificationService;


    /**
     * @var \App\Domains\Employee\Services\EmployeeService
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
        $this->verificationService = new EmployeeVerificationService();
        $this->employeeService = new EmployeeService();
        $this->faker = Factory::create();
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
        $verification = $this->getVerifiedProcess($email);
        $profile = EmployeeProfileFactory::make();
        $employee = $this->employeeService->register($verification->getId(), $profile, $this->employeePassword);
        $I->assertInstanceOf(Employee::class, $employee);
        $I->assertEquals($profile, $employee->getProfile());
        $I->assertEquals($employee->getContacts()->getEmail(), $email);
        $I->assertTrue($employee->isAdmin());
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
     * Create new employee and try to find it by passing login (this also checks for login creation from the companyId and email of employee)
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
        $findResult = $this->employeeService->findByCompanyIdAndEmail($employee->getCompany()->getId(), $employee->getContacts()->getEmail());
        $I->assertEquals($employee, $findResult);
        $I->wantToTest('Passing wrong company id leads to finding nothing as passing wrong email');
        $I->expectException(CompanyNotFound::class, function () use ($employee) {
            $this->employeeService->findByCompanyIdAndEmail('1'.$employee->getCompany()->getId(), $employee->getContacts()->getEmail());
        });
        $I->assertEmpty($this->employeeService->findByCompanyIdAndEmail($employee->getCompany()->getId(), '1'.$employee->getContacts()->getEmail()));
    }

    /**
     * Find employee by verificationId
     *
     * @param UnitTester $I
     */
    public function findByVerificationId(UnitTester $I)
    {
        $I->wantTo('Find existing employee by verification id');
        $email = $this->faker->email;
        $verification = $this->getVerifiedProcess($email);
        $this->dm->persist($verification);
        $this->dm->flush($verification);
        $employee = $this->registerEmployee($verification);
        $result = $this->employeeService->findByVerificationId($verification->getId());
        $I->assertEquals($employee->getId(), $result->first()->getId());
        $I->wantToTest('Passing wrong id throws 404');
        $I->expectException(EmployeeVerificationNotFound::class , function () use ($verification) {
            $this->employeeService->findByVerificationId('z' . $verification->getId());
        });
    }

    /**
     * Find employees with matching email and password combination
     *
     * @param UnitTester $I
     */
    public function findByEmailAndPassword(UnitTester $I)
    {
        $verification1 = $this->getVerifiedProcess($this->email);
        $employee1 = $this->registerEmployee($verification1);
        $verification2 = $this->getVerifiedProcess($this->email);
        $employee2 = $this->registerEmployee($verification2);

        $result = $this->employeeService->findByEmailAndPassword($employee1->getContacts()->getEmail(), $this->employeePassword);
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
        $verification1 = $this->getVerifiedProcess($this->email);
        $employee1 = $this->registerEmployee($verification1);
        $verification2 = $this->getVerifiedProcess($this->email);
        $employee2 = $this->registerEmployee($verification2);
        $this->dm->persist($employee1->getCompany());
        $this->dm->persist($employee2->getCompany());
        $companies = $this->employeeService->getMatchingCompanies([
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
        $employees = new Collection(
            [
                $this->registerEmployee(),
                $this->registerEmployee(),
                $this->registerEmployee()
            ]
        );

        $companies = $this->employeeService->getEmployeesCompanies($employees);
        $I->assertEquals(3, $companies->count());
        $I->assertContains($employees->random()->getCompany(), $companies);
    }


    /**
     * Find employee matching companyId and verificationId
     *
     * @param UnitTester $I
     */
    public function matchVerificationAndCompany(UnitTester $I)
    {
        $I->wantTo('Find active employee by company and verificationId to imitate RestorePasswordRequest');
        $company = $this->getCompany();
        $this->dm->persist($company);
        $profile = EmployeeProfileFactory::make();
        $verification = RestorePasswordVerification::make($this->email);
        $verification->associateCompany($company);
        $verification->verifyEmail($verification->getEmailCode());
        $this->dm->persist($verification->getVerification());
        $this->dm->flush();
        $employee = $this->employeeService->register($verification->getId(), $profile, $this->employeePassword);
        $match = $this->employeeService->matchVerificationAndCompany($verification->getId(), $company->getId());
        $I->assertEquals($employee, $match);
        $I->assertEquals($company, $match->getCompany());
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
        $I->expectException(PasswordMismatchException::class, function () use ($employee, $newPass) {
            $this->employeeService->changePassword($employee, $newPass, 'test' . $this->employeePassword);
        });
        $this->employeeService->changePassword($employee, $newPass, $this->employeePassword);
        $I->assertTrue($employee->checkPassword($newPass));
    }



    /**
     * Register new Employee
     * @param null $verification
     * @return Employee
     */
    private function registerEmployee($verification = null)
    {
        if ($verification === null) {
            $email = $this->faker->email;
            $verification = $this->getVerifiedProcess($email);
        }
        $profile = EmployeeProfileFactory::make();
        return $this->employeeService->register($verification->getId(), $profile, $this->employeePassword);
    }

    /**
     * Makes verification process and automatically verifies it
     *
     * @param string $email
     * @return \App\Domains\Employee\Entities\EmployeeVerification
     */
    private function getVerifiedProcess(string $email)
    {
        $verificationProcess = new RegistrationVerification($this->verificationService->beginVerificationProcess($this->getCompany()));
        $verificationProcess->getVerification()->associateEmail($email);
        $verificationProcess->getVerification()->verifyEmail($verificationProcess->getVerification()->getEmailCode());
        $this->dm->persist($verificationProcess->getVerification());
        $this->dm->flush($verificationProcess->getVerification());
        return $verificationProcess->getVerification();
    }

    /**
     * @return \App\Domains\Company\Entities\Company
     */
    private function getCompany()
    {
        $company = CompanyFactory::make();
        $this->dm->persist($company);
        $this->dm->flush($company);
        return $company;
    }

}
