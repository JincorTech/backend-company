<?php

use App\Core\Interfaces\EmployeeVerificationReason;
use App\Core\Services\JWTService;
use Helper\Api;
use App\Core\Interfaces\MessengerServiceInterface;
use App\Core\Interfaces\IdentityInterface;
use JincorTech\VerifyClient\Interfaces\VerifyService;
use JincorTech\VerifyClient\ValueObjects\EmailVerificationDetails;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EmployeeRegisterCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function employeeExist(ApiTester $I)
    {
        $messengerMock = Mockery::mock(MessengerServiceInterface::class);
        $messengerMock->shouldReceive('register')->once()->andReturn(true);
        $I->haveInstance(MessengerServiceInterface::class, $messengerMock);

        $jwtServiceMock = Mockery::mock(JWTService::class);
        $jwtServiceMock->shouldReceive('getCompanyId')->once()->andReturn('9fcad7c5-f84e-4d43-b35c-05e69d0e0362');
        $jwtServiceMock->shouldReceive('getData')->andReturn([
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
            'email' => 'test2@test.com',
            'reason' => EmployeeVerificationReason::REASON_REGISTER
        ]);
        $jwtServiceMock->shouldReceive('makeRegistrationToken')->andReturn('token');
        $I->haveInstance(JWTService::class, $jwtServiceMock);

        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')
            //->once()
            ->andThrow(new HttpException(
                500,
                trans('exceptions.employee.already_exists', [
                    'email' => 'test2@test.com',
                    'company' => 'Test Company'
                ])
            ));

        $I->haveInstance(IdentityInterface::class, $identityMock);

        $I->wantTo('Register new employee with existing email and receive 500 error code');
        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'password' => 'Password1',
            'position' => 'Wizard',
            'email' => 'test2@test.com',
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOm51bGwsImF1ZCI6bnVsbCwiaWF0IjoxNTExNjE1MDU5LCJleHAiOjE1MTI4MjQ2NTksImNvbXBhbnlOYW1lIjoiVklNRU80IiwiY29tcGFueUlkIjoiYjE0MmQyNmYtZTUxYS00YTM1LWFlY2UtY2UyZjAwOTYwMWU3In0.w5qGe_WkVKUw3Y8i9ACNUeg7bC4lCLuYWj7RFoldC-U'
        ]);

        $I->canSeeResponseCodeIs(500);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.already_exists', [
                'email' => 'test2@test.com',
                'company' => 'Test Company'
            ]),
            'status_code' => 500,
        ]);
    }

    public function success(ApiTester $I)
    {
        $I->wantTo('Register new employee with correct data and receive success response');

        $messengerMock = Mockery::mock(MessengerServiceInterface::class);
        $messengerMock->shouldReceive('register')->once()->andReturn(true);
        $I->haveInstance(MessengerServiceInterface::class, $messengerMock);

        $jwtServiceMock = Mockery::mock(JWTService::class);
        $jwtServiceMock->shouldReceive('makeRegistrationToken')->once()->andReturn('token');
        $jwtServiceMock->shouldReceive('getCompanyId')->once()->andReturn('9fcad7c5-f84e-4d43-b35c-05e69d0e0362');
        $jwtServiceMock->shouldReceive('getData')->andReturn([
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
            'email' => 'ivan@wizard.com',
            'reason' => EmployeeVerificationReason::REASON_REGISTER
        ]);
        $I->haveInstance(JWTService::class, $jwtServiceMock);

        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')->once()->andReturn(true);
        $identityMock->shouldReceive('login')->once()->andReturn('123');
        $I->haveInstance(IdentityInterface::class, $identityMock);

        $verifyMock = Mockery::mock(VerifyService::class);
        $verifyMock->shouldReceive('initiate')->once()->andReturn(
            new EmailVerificationDetails([
                'status' => '200',
                'verificationId' => 'd3d548c5-2c7d-4ae0-9271-8e41b7f03714',
                'expiredOn' => 12345678,
                'consumer' => 'ivan@wizard.com'
            ])
        );
        $I->haveInstance(VerifyService::class, $verifyMock);

        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'password' => 'Cm3jpmrt7c',
            'position' => 'Wizard',
            'email' => 'ivan@wizard.com',
            'token' => 'token',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('data.employee.id');
        $I->seeResponseJsonMatchesJsonPath('data.employee.profile.name');
        $I->seeResponseJsonMatchesJsonPath('data.employee.profile.firstName');
        $I->seeResponseJsonMatchesJsonPath('data.employee.profile.lastName');
        $I->seeResponseJsonMatchesJsonPath('data.employee.profile.position');
    }

    public function successPasswordPunctuation(ApiTester $I)
    {
        $I->wantTo('Register new employee with correct data and receive success response');

        $messengerMock = Mockery::mock(MessengerServiceInterface::class);
        $messengerMock->shouldReceive('register')->once()->andReturn(true);
        $I->haveInstance(MessengerServiceInterface::class, $messengerMock);

        $jwtServiceMock = Mockery::mock(JWTService::class);
        $jwtServiceMock->shouldReceive('makeRegistrationToken')->once()->andReturn('token');
        $jwtServiceMock->shouldReceive('getCompanyId')->once()->andReturn('9fcad7c5-f84e-4d43-b35c-05e69d0e0362');
        $jwtServiceMock->shouldReceive('getData')->andReturn([
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
            'email' => 'ivan@wizard.com',
            'reason' => EmployeeVerificationReason::REASON_REGISTER
        ]);
        $I->haveInstance(JWTService::class, $jwtServiceMock);

        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')->once()->andReturn(true);
        $identityMock->shouldReceive('login')->once()->andReturn('123');
        $I->haveInstance(IdentityInterface::class, $identityMock);

        $verifyMock = Mockery::mock(VerifyService::class);
        $verifyMock->shouldReceive('initiate')->once()->andReturn(
            new EmailVerificationDetails([
                'status' => '200',
                'verificationId' => 'd3d548c5-2c7d-4ae0-9271-8e41b7f03714',
                'expiredOn' => 12345678,
                'consumer' => 'ivan@wizard.com'
            ])
        );
        $I->haveInstance(VerifyService::class, $verifyMock);

        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'password' => "Cm3jpmrt7c!@#$%^&*()`~[]{}'\"?/\<>,.|",
            'position' => 'Wizard',
            'email' => 'ivan@wizard.com',
            'token' => 'token',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('data.employee.id');
        $I->seeResponseJsonMatchesJsonPath('data.employee.profile.name');
        $I->seeResponseJsonMatchesJsonPath('data.employee.profile.firstName');
        $I->seeResponseJsonMatchesJsonPath('data.employee.profile.lastName');
        $I->seeResponseJsonMatchesJsonPath('data.employee.profile.position');
    }

    public function passwordIncorrectFormat(ApiTester $I)
    {
        $I->wantTo('Register new employee with incorrect password format and receive 422 response code');
        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'password' => 'cm3jpmrt7c',
            'position' => 'Wizard of Hogwarts School of Witchcraft and Wizardry',
            'email' => 'ivan@wizard.com',
            'token' => 'token',
        ]);

        $attrName = trans('password');

        $message = trans('validation.regex', [
            'attribute' => $attrName,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'password' => [
                $message,
            ],
        ]);
    }

    public function passwordTooShort(ApiTester $I)
    {
        $I->wantTo('Register new employee with too short (less than 6 chars) password and receive 422 response code');
        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'password' => 'C123m',
            'position' => 'Wizard of Hogwarts School of Witchcraft and Wizardry',
            'email' => 'ivan@wizard.com',
            'token' => 'token',
        ]);

        $attrName = trans('password');
        $message = trans('validation.min.string', [
            'attribute' => $attrName,
            'min' => 8,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'password' => [
                $message,
            ],
        ]);
    }

    public function passwordNotSet(ApiTester $I)
    {
        $I->wantTo('Register new employee without password and receive 422 response code');
        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'position' => 'Wizard of Hogwarts School of Witchcraft and Wizardry',
            'email' => 'ivan@wizard.com',
            'token' => 'token',
        ]);

        $attrName = trans('password');
        $message = trans('validation.required', [
            'attribute' => $attrName,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'password' => [
                $message,
            ],
        ]);
    }

    public function positionTooLong(ApiTester $I)
    {
        $I->wantTo('Register new employee with too long position (60 chars) and receive 422 response code');
        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'password' => 'Cm3jpmrt7c',
            'position' => Api::generateRandomString(61),
            'email' => 'ivan@wizard.com',
            'token' => 'token',
        ]);

        $attrName = trans('position');

        $message = trans('validation.max.string', [
            'attribute' => $attrName,
            'max' => 60,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'position' => [
                $message,
            ],
        ]);
    }

    public function positionTooShort(ApiTester $I)
    {
        $I->wantTo('Register new employee with too short position (1 char) and receive 422 response code');
        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'password' => 'Cm3jpmrt7c',
            'position' => 'a',
            'email' => 'ivan@wizard.com',
            'token' => 'token',
        ]);

        $attrName = trans('position');

        $message = trans('validation.min.string', [
            'attribute' => $attrName,
            'min' => 2,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'position' => [
                $message,
            ],
        ]);
    }

    public function positionNotSet(ApiTester $I)
    {
        $I->wantTo('Register new employee without position and receive 422 response code');
        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'password' => 'Cm3jpmrt7c',
            'email' => 'ivan@wizard.com',
            'token' => 'token',
        ]);

        $attrName = trans('position');

        $message = trans('validation.required', [
            'attribute' => $attrName,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'position' => [
                $message,
            ],
        ]);
    }

    public function firstNameTooShort(ApiTester $I)
    {
        $I->wantTo('Register new employee with too short first name (1 char) and receive 422 response code');
        $I->sendPOST('employee/register', [
            'firstName' => 'I',
            'lastName' => 'Ivanov',
            'password' => 'Cm3jpmrt7c',
            'position' => 'Wizard',
            'email' => 'ivan@wizard.com',
            'token' => 'token',
        ]);

        $attrName = trans('first name');

        $message = trans('validation.min.string', [
            'attribute' => $attrName,
            'min' => 2,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'firstName' => [
                $message,
            ],
        ]);
    }

    public function firstNameNotSet(ApiTester $I)
    {
        $I->wantTo('Register new employee without first name and receive 422 response code');
        $I->sendPOST('employee/register', [
            'lastName' => 'Ivanov',
            'password' => 'Cm3jpmrt7c',
            'position' => 'Wizard',
            'email' => 'ivan@wizard.com',
            'token' => 'token',
        ]);

        $attrName = trans('first name');

        $message = trans('validation.required', [
            'attribute' => $attrName,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'firstName' => [
                $message,
            ],
        ]);
    }

    public function lastNameNotSet(ApiTester $I)
    {
        $I->wantTo('Register new employee without last name and receive 422 response code');
        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'password' => 'Cm3jpmrt7c',
            'position' => 'Wizard',
            'email' => 'ivan@wizard.com',
            'token' => 'token',
        ]);

        $attrName = trans('last name');

        $message = trans('validation.required', [
            'attribute' => $attrName,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'lastName' => [
                $message,
            ],
        ]);
    }

    public function lastNameTooShort(ApiTester $I)
    {
        $I->wantTo('Register new employee with too short last name (1 char) and receive 422 response code');
        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'I',
            'password' => 'Cm3jpmrt7c',
            'position' => 'Wizard',
            'email' => 'ivan@wizard.com',
            'token' => 'token',
        ]);

        $attrName = trans('last name');

        $message = trans('validation.min.string', [
            'attribute' => $attrName,
            'min' => 2,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'lastName' => [
                $message,
            ],
        ]);
    }
}
