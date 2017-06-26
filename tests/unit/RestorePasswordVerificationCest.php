<?php
use App\Domains\Employee\EntityDecorators\RestorePasswordVerification;

class RestorePasswordVerificationCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    public function testCompletelyVerifiedTrue(UnitTester $I)
    {
        $verification = EmployeeVerificationFactory::makeVerified();
        $invitation = new RestorePasswordVerification($verification);
        $I->assertTrue($invitation->completelyVerified());
    }

    public function testCompletelyVerifiedFalse(UnitTester $I)
    {
        $verification = EmployeeVerificationFactory::make();
        $invitation = new RestorePasswordVerification($verification);
        $I->assertFalse($invitation->completelyVerified());
    }
}
