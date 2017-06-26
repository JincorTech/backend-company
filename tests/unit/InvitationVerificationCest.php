<?php
use App\Domains\Employee\EntityDecorators\InvitationVerification;

class InvitationVerificationCest
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
        $invitation = new InvitationVerification($verification);
        $I->assertTrue($invitation->completelyVerified());
    }

    public function testCompletelyVerifiedFalse(UnitTester $I)
    {
        $verification = EmployeeVerificationFactory::make();
        $invitation = new InvitationVerification($verification);
        $I->assertFalse($invitation->completelyVerified());
    }
}
