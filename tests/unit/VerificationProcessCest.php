<?php

use App\Domains\Employee\EntityDecorators\InvitationVerification;
use App\Domains\Employee\EntityDecorators\RegistrationVerification;
use App\Domains\Employee\EntityDecorators\RestorePasswordVerification;
use App\Domains\Employee\Entities\EmployeeVerification;
use Faker\Factory;
use App\Domains\Employee\Exceptions\EmailPinIncorrect;

class VerificationProcessCest
{

    private $faker;


    public function __construct()
    {
        $this->faker = Factory::create();
    }


    /**
     * We can create Invitation verifications
     *
     * @param UnitTester $I
     */
    public function invitationVerification(UnitTester $I)
    {
        $email = $this->faker->email;
        $employee = EmployeeFactory::make();
        $verification = InvitationVerification::make($employee, $email);
        $I->assertEquals($verification->getEmail(), $email);
        $I->assertEquals($verification->getCompany(), $employee->getCompany());
        $this->codeVerification($I, $verification);
    }

    /**
     * We can create registration verification as well
     *
     * @param UnitTester $I
     */
    public function registrationVerification(UnitTester $I)
    {
        $email = $this->faker->email;
        $company = CompanyFactory::make();
        $verification = RegistrationVerification::make($company);
        $I->assertEquals($verification->getCompany(), $company);
        $this->codeVerification($I, $verification);
    }

    /**
     * We can create restore password verifications also
     *
     * @param UnitTester $I
     */
    public function restorePasswordVerification(UnitTester $I)
    {
        $email = $this->faker->email;
        $verification = RestorePasswordVerification::make($email);
        $I->assertEquals($verification->getEmail(), $email);
        $this->codeVerification($I, $verification->getVerification());
    }

    /**
     * We can decorate
     *
     * @param UnitTester $I\
     */
    public function decorateInvitation(UnitTester $I)
    {
        $email = $this->faker->email;
        $employee = EmployeeFactory::make();
        $verification = InvitationVerification::make($employee, $email);
        $decor = new InvitationVerification($verification);
        $I->assertEquals($verification, $decor->getVerification());
        $I->assertEquals($verification->getCompany(), $employee->getCompany());
    }

    /**
     * We can decorate
     *
     * @param UnitTester $I
     */
    public function decorateRegistration(UnitTester $I)
    {
        $company = CompanyFactory::make();
        $verification = RegistrationVerification::make($company);
        $decor = new RegistrationVerification($verification);
        $I->assertEquals($verification, $decor->getVerification());
        $I->assertEquals($verification->getCompany(), $company);
    }

    /**
     * We can decorate
     *
     * @param UnitTester $I
     */
    public function decoratePasswordRestore(UnitTester $I)
    {
        $email = $this->faker->email;
        $verification = RestorePasswordVerification::make($email);
        $I->assertEquals($verification->getEmail(), $email);
    }

    /**
     * @param UnitTester $I
     * @param $verification
     */
    private function codeVerification(UnitTester $I, EmployeeVerification $verification)
    {
        $I->assertFalse($verification->isEmailVerified());
        $I->expectException(EmailPinIncorrect::class, function () use ($verification) {
            $verification->verifyEmail('123456');
        });
        $I->assertFalse($verification->isEmailVerified());
        $verification->verifyEmail($verification->getEmailCode());
        $I->assertTrue($verification->isEmailVerified());
    }

}
