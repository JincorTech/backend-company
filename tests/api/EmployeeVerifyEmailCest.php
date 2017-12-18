<?php

use App\Core\Services\JWTService;
use JincorTech\VerifyClient\Exceptions\InvalidCodeException;
use JincorTech\VerifyClient\Interfaces\VerifyService;
use JincorTech\VerifyClient\ValueObjects\EmailVerificationDetails;
use JincorTech\VerifyClient\ValueObjects\VerificationResult;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmployeeVerifyEmailCest
{
    public function sendVerification(ApiTester $I)
    {
        $verifyMock = Mockery::mock(VerifyService::class);
        $verifyMock->shouldReceive('initiate')->once()->andReturn(
            new EmailVerificationDetails([
                'status' => '200',
                'verificationId' => '6b90fa0c-7912-452c-bddf-e2c718440251',
                'expiredOn' => 12345678,
                'consumer' => 'notverified@tester.com'
            ])
        );
        $verifyMock->shouldReceive('getVerification')->andReturn(
            new EmailVerificationDetails([
                'status' => '200',
                'verificationId' => '6b90fa0c-7912-452c-bddf-e2c718440251',
                'expiredOn' => 12345678,
                'consumer' => 'notverified@tester.com',
                'payload' => 'token'
            ])
        );
        $I->haveInstance(VerifyService::class, $verifyMock);

        $data = ['employeeId' => '1e47c155-3a77-4dc7-9d4d-34fc2afa6b31',
            'reason' => 'register'];

        $jwtServiceMock = Mockery::mock(JWTService::class);
        $jwtServiceMock->shouldReceive('getData')->once()->andReturn($data);
        $jwtServiceMock->shouldReceive('makeRegistrationToken')->once()->andReturn('token');
        $I->haveInstance(JWTService::class, $jwtServiceMock);



        $I->wantTo('Request verification code and receive it to my email address');

        $I->sendGET('employee/verifyEmail', [
            'email' => 'notverified@tester.com',
            'verificationId' => '6b90fa0c-7912-452c-bddf-e2c718440251',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'data' => [
                'id' => '6b90fa0c-7912-452c-bddf-e2c718440251',
                'companyId' => '2862897b-3e88-4230-a9b7-570917704f56',
                'email' => [
                    'value' => 'notverified@tester.com',
                    'isVerified' => false
                ],
                'phone' => [
                    'value' => null,
                    'isVerified' => false
                ],
            ],
        ]);
    }

    public function sendVerificationIncorrectId(ApiTester $I)
    {
        $verifyMock = Mockery::mock(VerifyService::class);
        $verifyMock->shouldReceive('getVerification')->andThrow(
            new NotFoundHttpException(
                '404'
            )
        );
        $I->haveInstance(VerifyService::class, $verifyMock);

        $I->wantTo('Request verification code with incorrect verification ID and receive 404 error code');

        $I->sendGET('employee/verifyEmail', [
            'email' => 'notverified@tester.com',
            'verificationId' => '6b90fa0c-7912-452c-bddf-e2c718440252',
        ]);

        $I->canSeeResponseCodeIs(404);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.verification.not_found', [
                'verification' => '6b90fa0c-7912-452c-bddf-e2c718440252',
            ]),
            'status_code' => 404,
        ]);
    }

    public function verifyEmailCorrectCode(ApiTester $I)
    {
        $data = ['employeeId' => '1e47c155-3a77-4dc7-9d4d-34fc2afa6b31',
            'reason' => 'register'];

        $jwtServiceMock = Mockery::mock(JWTService::class);
        $jwtServiceMock->shouldReceive('getData')->once()->andReturn($data);
        $I->haveInstance(JWTService::class, $jwtServiceMock);

        $verifyMock = Mockery::mock(VerifyService::class);
        $verifyMock->shouldReceive('validate')->once()->andReturn(
            new VerificationResult([
                'status' => 200,
                'data' => [
                    'consumer' => 'notverified@tester.com',
                    'verificationId' => '6b90fa0c-7912-452c-bddf-e2c718440251',
                    'expiredOn' => 1234567,
                    'payload' => 'token',
                ]
            ])
        );
        $I->haveInstance(VerifyService::class, $verifyMock);

        $I->wantTo('Verify my email with correct code in order to continue registration process');

        $I->sendPOST('employee/verifyEmail', [
            'verificationCode' => '318379',
            'verificationId' => '6b90fa0c-7912-452c-bddf-e2c718440251',
        ]);


        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'data' => [
                'id' => '6b90fa0c-7912-452c-bddf-e2c718440251',
                'companyId' => '2862897b-3e88-4230-a9b7-570917704f56',
                'email' => [
                    'value' => 'notverified@tester.com',
                    'isVerified' => true,
                ],
                'phone' => [
                    'value' => null,
                    'isVerified' => false
                ],
            ],
        ]);
    }

    public function verifyEmailIncorrectCode(ApiTester $I)
    {
        $verifyMock = Mockery::mock(VerifyService::class);
        $verifyMock->shouldReceive('validate')->once()->andThrow(
            new InvalidCodeException('Invalid Code')
        );
        $I->haveInstance(VerifyService::class, $verifyMock);

        $I->wantTo('Verify my email with incorrect verification code and receive 401 response code');

        $I->sendPOST('employee/verifyEmail', [
            'verificationCode' => '318300',
            'verificationId' => '6b90fa0c-7912-452c-bddf-e2c718440251',
        ]);

        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(401);
        $I->canSeeResponseContainsJson([
            'success' => false,
            'message' => trans('exceptions.verification.code.incorrect'),
        ]);
    }
}
