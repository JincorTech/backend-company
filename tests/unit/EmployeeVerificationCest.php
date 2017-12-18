<?php

use App\Domains\Employee\Entities\EmployeeVerification;
use App\Core\Interfaces\IdentityInterface;

class EmployeeVerificationCest
{
    public function _before(UnitTester $I)
    {
        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')->once()->andReturn(true);
        App::instance(IdentityInterface::class, $identityMock);
    }

    public function _after(UnitTester $I)
    {
    }

    public function canCreate(UnitTester $I)
    {
        $verification = new EmployeeVerification(EmployeeVerification::REASON_REGISTER);

        $I->assertNotEmpty($verification->getId());
        $I->assertInstanceOf(\DateTime::class, $verification->getCreatedAt());
        $I->assertFalse($verification->isEmailVerified());
        $I->assertEquals(EmployeeVerification::REASON_REGISTER, $verification->getReason());
    }

    public function canVerify(UnitTester $I)
    {
        $verification = new EmployeeVerification(EmployeeVerification::REASON_REGISTER);

        $verification->setVerifyEmail(true);

        $I->assertInstanceOf(\DateTime::class, $verification->getEmailVerifiedAt());
        $I->assertTrue($verification->completelyVerified());
    }
}
