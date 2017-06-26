<?php
use App\Core\Interfaces\IdentityInterface;

class EmployeeChangePasswordCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function changeUsingValidVerificationId(ApiTester $I)
    {
        $I->wantTo('Change my password using valid verification ID and receive 200 response code');

        $mock = Mockery::mock(IdentityInterface::class);
        $mock->shouldReceive('login')->andReturn('token');
        $mock->shouldReceive('register')->andReturn(true);
        $I->haveInstance(IdentityInterface::class, $mock);

        $I->sendPUT('employee/changePassword', [
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
            'verificationId' => '3e497dd3-4d2f-4eee-84ce-02516982b1ff',
            'password' => 'Cm3jpmrt7c',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'data' => [
                'employee' => [
                    'id' => '63e88d9d-a79e-4705-9c8f-8712b71b53f8',
                    'profile' => [
                        'name' => 'John Doe',
                        'firstName' => 'John',
                        'lastName' => 'Doe',
                        'position' => 'Tester',
                        'role' => 'company-admin',
                        'avatar' => 'http://existing2.avatar',
                    ],
                    'admin' => true,
                    'contacts' => [
                        'email' => 'test2@test.com',
                        'phone' => null,
                    ],
                    'company' => [
                        'id' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
                        'legalName' => 'Test Company',
                        'profile' => [
                            'brandName' => [],
                            'description' => null,
                            'picture' => null,
                            'links' => [],
                            'email' => null,
                            'phone' => null,
                            'address' => [
                                'country' => [
                                    'id' => 'c699fc1a-ec7f-4021-9102-31ff03c5624a',
                                    'name' => 'Россия',
                                ],
                                'city' => null,
                                'formattedAddress' => 'Москва, ул. Алая, д. 15, оф. 89, 602030',
                            ],
                        ],
                        'economicalActivityTypes' => [],
                        'companyType' => [
                            'id' => '4f021f7f-23bd-4317-a40b-086bf8e6a98d',
                            'name' => 'Частная компания',
                            'code' => 'BT1',
                        ],
                        'employeesCount' => 2,
                    ],
                ],
            ],
        ]);

        $I->canSeeResponseJsonMatchesJsonPath('$.data.token');
    }

    public function changeByValidVerificationIdIncorrectPasswordFormat(ApiTester $I)
    {
        $I->wantTo('Change my password using valid verification ID but with incorrect password format. 422 response code expected');
        $I->sendPUT('employee/changePassword', [
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
            'verificationId' => '3e497dd3-4d2f-4eee-84ce-02516982b1ff',
            'password' => 'cm3jpmrt7c'
        ]);

        $attr = trans('password');
        $message = trans('validation.regex', [
            'attribute' => $attr,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'password' => [
                $message,
            ],
        ]);
    }

    public function changeByValidVerificationIdTooShortPassword(ApiTester $I)
    {
        $I->wantTo('Change my password using valid verification ID but with too short password. 422 response code expected');
        $I->sendPUT('employee/changePassword', [
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
            'verificationId' => '3e497dd3-4d2f-4eee-84ce-02516982b1ff',
            'password' => 'Cm3jp'
        ]);

        $attrName = trans('password');
        $message = trans('validation.min.string', [
            'attribute' => $attrName,
            'min' => 6,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'password' => [
                $message,
            ],
        ]);
    }

    public function changeByInvalidVerificationId(ApiTester $I)
    {
        $I->wantTo('Change my password using invalid verification ID. 401 response code expected');
        $I->sendPUT('employee/changePassword', [
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
            'verificationId' => '3e497dd3-4d2f-4eee-84ce-02516982b123',
            'password' => 'Cm3jpHnnnn'
        ]);

        $I->canSeeResponseCodeIs(401);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.verification.failed'),
        ]);
    }

    public function changeUsingValidOldPassword(ApiTester $I)
    {
        $token = '123';

        $I->wantTo('Change my password using valid old password and receive 200 response code');

        $I->amAuthorizedAsTestCompanyAdmin($token);

        $I->sendPUT('employee/changePassword', [
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
            'oldPassword' => 'Cm3jpmrt7c',
            'password' => 'Cm3jpmrt7c123'
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'data' => [
                'employee' => [
                    'id' => '63e88d9d-a79e-4705-9c8f-8712b71b53f8',
                    'profile' => [
                        'name' => 'John Doe',
                        'firstName' => 'John',
                        'lastName' => 'Doe',
                        'position' => 'Tester',
                        'role' => 'company-admin',
                        'avatar' => 'http://existing2.avatar',
                    ],
                    'admin' => true,
                    'contacts' => [
                        'email' => 'test2@test.com',
                        'phone' => null,
                    ],
                    'company' => [
                        'id' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
                        'legalName' => 'Test Company',
                        'profile' => [
                            'brandName' => [],
                            'description' => null,
                            'picture' => null,
                            'links' => [],
                            'email' => null,
                            'phone' => null,
                            'address' => [
                                'country' => [
                                    'id' => 'c699fc1a-ec7f-4021-9102-31ff03c5624a',
                                    'name' => 'Россия',
                                ],
                                'city' => null,
                                'formattedAddress' => 'Москва, ул. Алая, д. 15, оф. 89, 602030',
                            ],
                        ],
                        'economicalActivityTypes' => [],
                        'companyType' => [
                            'id' => '4f021f7f-23bd-4317-a40b-086bf8e6a98d',
                            'name' => 'Частная компания',
                            'code' => 'BT1',
                        ],
                        'employeesCount' => 2,
                    ],
                ],
            ],
        ]);

        $I->canSeeResponseJsonMatchesJsonPath('$.data.token');
    }

    public function changeUsingInvalidOldPassword(ApiTester $I)
    {
        $token = '123';

        $I->wantTo('Change my password using invalid old password and receive 403 response code');

        $I->amAuthorizedAsTestCompanyAdmin($token);

        $I->sendPUT('employee/changePassword', [
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
            'oldPassword' => 'Randomstuff123',
            'password' => 'Cm3jpmrt7c123'
        ]);

        $I->canSeeResponseCodeIs(403);
        $I->canSeeResponseIsJson();
    }
}
