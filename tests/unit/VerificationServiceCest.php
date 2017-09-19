<?php

use App\Core\Services\Verification\EmailVerificationData;
use App\Core\Services\Verification\EmailVerificationVerificationMethod;
use App\Core\Services\Verification\RestVerificationService;
use App\Core\Services\Verification\VerificationIdentifier;
use App\Core\Services\Verification\VerificationMethod;
use App\Core\Services\Verification\VerificationService;

class VerificationServiceCest
{
    const EMAIL_CONSUMER = 'test@test.com';
    const EMAIL_TEMPLATE = '{{{CODE}}}';

    const EMAIL_VERIFICATION_ID = 'd6b78279-db85-467e-b965-c938d043ffac';
    const EMAIL_VERIFICATION_CODE = 'boCMVNxsP6fV192zkjpNkLS8M';
    const EMAIL_VERIFICATION_EXPIRED_ON = '60 min';

    /**
     * @return Mockery\MockInterface
     */
    private function createMockedRestService()
    {
        $restMockedService = \Mockery::mock(RestVerificationService::class);
        $restMockedService->shouldAllowMockingProtectedMethods();
        return $restMockedService;
    }

    /**
     * @return int
     */
    private function nowPlusOneHour(): int
    {
        return time() + 3600;
    }

    /**
     * @return EmailVerificationVerificationMethod
     */
    private function createFilledEmailMethod()
    {
        return EmailVerificationVerificationMethod::buildDefault(
            self::EMAIL_CONSUMER,
            'Subject',
            'from@from.com',
            'from',
            self::EMAIL_TEMPLATE
        )->setGenerateCode(['DIGITS', "alphas", "ALPHAS"], 32)
            ->setForcedVerificationId(new VerificationIdentifier(self::EMAIL_VERIFICATION_ID))
            ->setPolicy(self::EMAIL_VERIFICATION_EXPIRED_ON);
    }

    /**
     * @param UnitTester $I
     */
    public function itWillCreateRestVerificationService(UnitTester $I)
    {
        $I->assertInstanceOf(VerificationService::class, new RestVerificationService());
    }

    /**
     * @param UnitTester $I
     */
    public function itWillCreateEmailVerificationMethod(UnitTester $I)
    {
        $emailMethod = new EmailVerificationVerificationMethod();

        $I->assertInstanceOf(VerificationMethod::class, $emailMethod);
        $I->assertEquals(
            $emailMethod->getMethodType(),
            EmailVerificationVerificationMethod::METHOD_TYPE
        );
    }

    /**
     * @param UnitTester $I
     */
    public function itWillFormatEmailVerificationAsRequestArray(UnitTester $I)
    {
        $I->assertArrayHasKey(
            'consumer',
            (new EmailVerificationVerificationMethod())->getRequestParameters()
        );
    }

    /**
     * @param UnitTester $I
     */
    public function itWillFillEmailVerificationMethodInstance(UnitTester $I)
    {
        $emailMethod = $this->createFilledEmailMethod();

        $I->assertEquals(
            self::EMAIL_CONSUMER,
            $I->getObjectPropertyValue($emailMethod, 'consumer')
        );
    }

    /**
     * @param UnitTester $I
     */
    public function itWillInitiateEmailVerification(UnitTester $I)
    {
        $restMockedService = $this->createMockedRestService();
        $restMockedService->makePartial()
            ->shouldReceive('doApiRequest')
            ->andReturn([
                'status' => 200,
                'verificationId' => VerificationServiceCest::EMAIL_VERIFICATION_ID,
                'expiredOn' => $this->nowPlusOneHour()
            ]);

        $verificationId = $restMockedService->initiate($this->createFilledEmailMethod());

        $I->assertEquals(self::EMAIL_VERIFICATION_ID, $verificationId->getVerificationId());
        $I->assertGreaterThan(time(), $verificationId->getExpiredOn());
    }

    /**
     * @param UnitTester $I
     */
    public function itWillSuccessValidateEmailVerification(UnitTester $I)
    {
        $restMockedService = $this->createMockedRestService();
        $restMockedService->makePartial()
            ->shouldReceive('doApiRequest')
            ->andReturn([
                'status' => 200
            ]);

        $I->assertTrue(
            $restMockedService->validate(
                new EmailVerificationData(
                    new VerificationIdentifier(self::EMAIL_VERIFICATION_ID),
                    self::EMAIL_VERIFICATION_CODE
                )
            )
        );
    }

    /**
     * @param UnitTester $I
     */
    public function itWillFailValidateEmailVerification(UnitTester $I)
    {
        $restMockedService = $this->createMockedRestService();
        $restMockedService->makePartial()
            ->shouldReceive('doApiRequest')
            ->andReturn([
                'status' => 422,
                'error' => 'Invalid code'
            ]);

        $I->assertFalse(
            $restMockedService->validate(
                new EmailVerificationData(
                    new VerificationIdentifier(self::EMAIL_VERIFICATION_ID),
                    'wrong code'
                )
            )
        );
    }
}
