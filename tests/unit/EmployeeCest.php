<?php

use App\Domains\Employee\Entities\Employee;
use App\Domains\Employee\Exceptions\EmployeeVerificationException;
use App\Domains\Company\Entities\Company;
use App\Domains\Employee\EntityDecorators\RestorePasswordVerification;
use App\Core\Interfaces\MessengerServiceInterface;
use App\Core\Interfaces\IdentityInterface;

class EmployeeCest
{

    public function _before(UnitTester $I)
    {
        $messengerMock = Mockery::mock(MessengerServiceInterface::class);
        $messengerMock->shouldReceive('register')->once()->andReturn(true);
        App::instance(MessengerServiceInterface::class, $messengerMock);

        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')->once()->andReturn(true);
        App::instance(IdentityInterface::class, $identityMock);
    }

    /**
     * Check if we can register employee
     *
     * @param UnitTester $I
     */
    public function canRegisterNewEmployee(UnitTester $I)
    {
        $password = 'test123';
        $profile = EmployeeProfileFactory::make();
        $verification = EmployeeVerificationFactory::make();
        $verification->verifyEmail($verification->getEmailCode());
        $employee = Employee::register($verification, $profile, $password);
        $I->assertInstanceOf(Employee::class, $employee);
        $I->assertEquals($profile, $employee->getProfile());
        $I->assertNotEquals($password, $employee->getPassword());
        $I->assertTrue($employee->checkPassword($password));
    }

    /**
     * Check if minimal profile is setting up well
     * @param UnitTester $I
     */
    public function testProfile(UnitTester $I)
    {
        $employee = EmployeeFactory::make();
        $I->assertNotEmpty($employee->getProfile()->getName());
        $I->assertNotEmpty($employee->getProfile()->getPosition());


    }

    /**
     * Test if contact is ok and not empty so we can generate logins
     *
     * @param UnitTester $I
     */
    public function testContact(UnitTester $I)
    {
        $employee = EmployeeFactory::make();
        $I->assertNotEmpty($employee->getContacts()->getEmail());
        $I->assertNotEmpty($employee->getContacts()->getPhone());
        $I->assertEquals($employee->getLogin(), $employee->getCompany()->getId() . ':' . $employee->getContacts()->getEmail());

    }


    /**
     * Test if the company has been attached to user correctly
     *
     * @param UnitTester $I
     */
    public function testCompany(UnitTester $I)
    {
        $employee = EmployeeFactory::make();
        $I->assertInstanceOf(Company::class, $employee->getCompany());
    }


    /**
     * Assert we not allow to register users with no companies
     *
     * @param UnitTester $I
     */
    public function canNotRegisterEmployeeWithoutCompany(UnitTester $I)
    {
        $password = 'test123';
        $profile = EmployeeProfileFactory::make();
        $verification = RestorePasswordVerification::make('test@test.com');
        $I->expectException(EmployeeVerificationException::class, function() use ($profile, $password, $verification) {
            Employee::register($verification->getVerification(), $profile, $password);
        });
    }

}
