<?php
use App\Domains\Employee\Entities\EmployeeVerification;

class EmployeeVerificationCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    public function canCreate(UnitTester $I)
    {
        $verification = new EmployeeVerification(EmployeeVerification::REASON_REGISTER);

        $I->assertNotEmpty($verification->getId());
        $I->assertInstanceOf(\DateTime::class, $verification->getCreatedAt());
        $I->assertTrue($verification->getEmailCode() >= 100000 && $verification->getEmailCode() <= 999999);
        $I->assertFalse($verification->isEmailVerified());
        $I->assertEquals(EmployeeVerification::REASON_REGISTER, $verification->getReason());
    }

    public function canVerify(UnitTester $I)
    {
        $verification = new EmployeeVerification(EmployeeVerification::REASON_REGISTER);

        $verification->verifyEmail($verification->getEmailCode());

        $I->assertInstanceOf(\DateTime::class, $verification->getEmailVerifiedAt());
        $I->assertTrue($verification->completelyVerified());
    }
}
