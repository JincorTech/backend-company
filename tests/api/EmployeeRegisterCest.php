<?php
use Helper\Api;
use App\Core\Interfaces\MessengerServiceInterface;
use App\Core\Interfaces\IdentityInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EmployeeRegisterCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function verificationIncorrect(ApiTester $I)
    {
        $I->wantTo('Register new employee with incorrect verification ID and receive 422 error code');
        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'password' => 'Cm3jpmrt7c',
            'verificationId' => '8a62229e-fc82-4018-be4b-83a5bca72452',
            'position' => 'Wizard',
            'email' => 'ivan@test.com',
        ]);
        $I->canSeeResponseCodeIs(404);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.verification.not_found', [
                'verification' => '8a62229e-fc82-4018-be4b-83a5bca72452',
            ]),
            'status_code' => 404,
        ]);
    }

    public function employeeExist(ApiTester $I)
    {
        $messengerMock = Mockery::mock(MessengerServiceInterface::class);
        $messengerMock->shouldReceive('register')->once()->andReturn(true);
        $I->haveInstance(MessengerServiceInterface::class, $messengerMock);

        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')
            ->once()
            ->andThrow(new HttpException(
                500,
                trans('exceptions.employee.already_exists', [
                    'email' => 'test2@test.com',
                    'company' => 'Test Company',
                ])
            ));

        $I->haveInstance(IdentityInterface::class, $identityMock);

        $I->wantTo('Register new employee with existing email and receive 500 error code');
        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'password' => 'Cm3jpmrt7c',
            'verificationId' => '4d668fe0-85d3-49d7-9277-9499c5a3024b',
            'position' => 'Wizard',
            'email' => 'ivan@test.com',
        ]);

        $I->canSeeResponseCodeIs(500);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.already_exists', [
                'email' => 'test2@test.com',
                'company' => 'Test Company',
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

        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')->once()->andReturn(true);
        $identityMock->shouldReceive('login')->once()->andReturn('123');
        $I->haveInstance(IdentityInterface::class, $identityMock);

        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'password' => 'Cm3jpmrt7c',
            'verificationId' => '2a5f87b8-273a-47f8-af20-786ffce71edc',
            'position' => 'Wizard',
            'email' => 'ivan@wizard.com',
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

        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')->once()->andReturn(true);
        $identityMock->shouldReceive('login')->once()->andReturn('123');
        $I->haveInstance(IdentityInterface::class, $identityMock);

        $I->sendPOST('employee/register', [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'password' => "Cm3jpmrt7c!@#$%^&*()`~[]{}'\"?/\<>,.|",
            'verificationId' => '2a5f87b8-273a-47f8-af20-786ffce71edc',
            'position' => 'Wizard',
            'email' => 'ivan@wizard.com',
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
            'verificationId' => '2a5f87b8-273a-47f8-af20-786ffce71edc',
            'position' => 'Wizard of Hogwarts School of Witchcraft and Wizardry',
            'email' => 'ivan@wizard.com',
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
            'verificationId' => '8a62229e-fc82-4018-be4b-83a5bca72452',
            'position' => 'Wizard of Hogwarts School of Witchcraft and Wizardry',
            'email' => 'ivan@wizard.com',
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
            'verificationId' => '8a62229e-fc82-4018-be4b-83a5bca72452',
            'position' => 'Wizard of Hogwarts School of Witchcraft and Wizardry',
            'email' => 'ivan@wizard.com',
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
            'verificationId' => '8a62229e-fc82-4018-be4b-83a5bca72452',
            'position' => Api::generateRandomString(61),
            'email' => 'ivan@wizard.com',
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
            'verificationId' => '8a62229e-fc82-4018-be4b-83a5bca72452',
            'position' => 'a',
            'email' => 'ivan@wizard.com',
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
            'verificationId' => '8a62229e-fc82-4018-be4b-83a5bca72452',
            'email' => 'ivan@wizard.com',
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
            'verificationId' => '8a62229e-fc82-4018-be4b-83a5bca72452',
            'position' => 'Wizard',
            'email' => 'ivan@wizard.com',
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
            'verificationId' => '8a62229e-fc82-4018-be4b-83a5bca72452',
            'position' => 'Wizard',
            'email' => 'ivan@wizard.com',
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
            'verificationId' => '8a62229e-fc82-4018-be4b-83a5bca72452',
            'position' => 'Wizard',
            'email' => 'ivan@wizard.com',
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
            'verificationId' => '8a62229e-fc82-4018-be4b-83a5bca72452',
            'position' => 'Wizard',
            'email' => 'ivan@wizard.com',
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
