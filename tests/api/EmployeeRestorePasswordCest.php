<?php

use JincorTech\VerifyClient\Interfaces\VerifyService;
use JincorTech\VerifyClient\ValueObjects\EmailVerificationDetails;

class EmployeeRestorePasswordCest
{
    public function success(ApiTester $I)
    {
        $verifyMock = Mockery::mock(VerifyService::class);
        $verifyMock->shouldReceive('initiate')->once()->andReturn(
            new EmailVerificationDetails([
                'status' => '200',
                'verificationId' => 'd3d548c5-2c7d-4ae0-9271-8e41b7f03714',
                'expiredOn' => 12345678,
                'consumer' => 'some@email.com'
            ])
        );
        $I->haveInstance(VerifyService::class, $verifyMock);

        $I->wantTo('Send a request to restore password with existing email to receive password restore link.');
        $I->sendPOST('employee/restorePassword', [
            'email' => 'test@test.com',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'data' => [
                'companyId' => null,
                'email' => [
                    'value' => 'test@test.com',
                    'isVerified' => false
                ],
                'phone' => [
                    'value' => null,
                    'isVerified' => false
                ],
            ],
        ]);
    }

    public function emailNotExist(ApiTester $I)
    {
        $I->wantTo('Send a request to restore password with not existing email and receive 404 response code.');
        $I->sendPOST('employee/restorePassword', [
            'email' => 'randomstuff@test.com',
        ]);

        $I->canSeeResponseCodeIs(404);
        $I->canSeeResponseIsJson();

        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.restore-password.notFound', [
                'email' => 'randomstuff@test.com',
            ]),
            'status_code' => 404,
        ]);
    }
}
