<?php

use App\Domains\Employee\Entities\Employee;
use App\Domains\Company\Entities\Company;
use App\Core\Interfaces\MessengerServiceInterface;
use App\Core\Interfaces\IdentityInterface;
use App\Domains\Employee\Exceptions\ContactNotFound;
use App\Domains\Employee\Exceptions\ContactAlreadyAdded;
use App\Domains\Employee\ValueObjects\EmployeeContact;
use App\Domains\Employee\ValueObjects\EmployeeRole;

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

        $jwtServiceMock = Mockery::mock(JWTService::class);
        $jwtServiceMock->shouldReceive('makeRegistrationToken')->once()->andReturn('token');
        $jwtServiceMock->shouldReceive('getCompanyId')->once()->andReturn('9fcad7c5-f84e-4d43-b35c-05e69d0e0362');
        App::instance(JWTService::class, $jwtServiceMock);

        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')->once()->andReturn(true);
        $identityMock->shouldReceive('login')->once()->andReturn('123');
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
        $company = CompanyFactory::make();
        $employeeContact = new EmployeeContact('test@test.com');
        $employee = Employee::register($company, $profile, $password, $employeeContact);
        $I->assertInstanceOf(Employee::class, $employee);
        $I->assertEquals($profile, $employee->getProfile());
        $I->assertNotEquals($password, $employee->getPassword());
        $I->assertTrue($employee->checkPassword($password));
        $I->assertInstanceOf(\DateTime::class, $employee->getRegisteredAt());

        //first employee of company is always registered as admin
        $I->assertEquals(EmployeeRole::ADMIN, $employee->getProfile()->scope);

        $I->assertEquals($employee->getCompany()->getRootDepartment()->getId(), $employee->getDepartmentId());
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
        $I->assertEquals(
            $employee->getLogin(),
            $employee->getCompany()->getId().':'.$employee->getContacts()->getEmail()
        );
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

    public function canAddContact(UnitTester $I)
    {
        $employee = EmployeeFactory::make();
        $contact = EmployeeFactory::make();
        $contact->activate();

        $employee->addContact($contact);

        $I->assertEquals(1, $employee->getContactList()->count());

        $expected = $contact;
        $actual = $employee->getContactList()->first()->getEmployee();
        $I->assertEquals($expected, $actual);

        $I->expectException(ContactAlreadyAdded::class, function () use ($employee, $contact) {
            $employee->addContact($contact);
        });
    }

    public function canDeleteContact(UnitTester $I)
    {
        $employee = EmployeeFactory::make();
        $contact1 = EmployeeFactory::make();
        $contact1->activate();

        $contact2 = EmployeeFactory::make();
        $contact2->activate();

        $employee->addContact($contact1);
        $employee->addContact($contact2);

        $deletedContactItem = $employee->getContactList()->last();

        $employee->deleteContact($contact2);
        $I->assertEquals(1, $employee->getContactList()->count());
        $I->assertFalse($employee->getContactList()->indexOf($deletedContactItem));

        $I->expectException(ContactNotFound::class, function () use ($employee, $contact2) {
            $employee->deleteContact($contact2);
        });
    }

    public function testEmployeeRoleAssignOnRegistration(UnitTester $I)
    {
        $company = CompanyFactory::makeMockWith1Employee();
        $profile = EmployeeProfileFactory::make();
        $password = 'test123';
        $email = 'test@test.com';

        $employeeContact = new EmployeeContact($email);
        $employee = Employee::register($company, $profile, $password, $employeeContact);

        //not first employee of company is registered not as admin
        $I->assertEquals(EmployeeRole::EMPLOYEE, $employee->getProfile()->scope);
    }

    public function canGetDeletedAt(UnitTester $I)
    {
        $employee = EmployeeFactory::make();
        $employee->deactivate();
        $I->assertInstanceOf(\DateTime::class, $employee->getDeletedAt());
    }

    public function canGetContactListFromDb(UnitTester $I)
    {
        $employee = EmployeeFactory::makeFromDb();
        $I->assertEquals(1, $employee->getContactList()->count());
    }

    public function testIsAddedToContactList(UnitTester $I)
    {
        $employee = EmployeeFactory::make();
        $contact1 = EmployeeFactory::make();
        $contact1->activate();
        $contact2 = EmployeeFactory::make();
        $employee->addContact($contact1);

        $I->assertTrue($employee->isAddedToContactList($contact1));
        $I->assertFalse($employee->isAddedToContactList($contact2));
    }
}
