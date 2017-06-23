<?php


class EmployeeColleaguesCest
{
    public function _before(ApiTester $I)
    {
        $token = '123'; //just random token
        $I->amAuthorizedAsJincorAdmin($token);
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function getColleagues(ApiTester $I)
    {
        $I->wantTo('Get list of my colleagues and receive success response');
        $I->sendGET('employee/colleagues');

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'data' => [
                'self' => [
                    'id' => '3e696895-ab1b-44ec-8646-86067877e38c',
                    'profile' => [
                        'name' => 'Admin Company 2',
                        'firstName' => 'Admin',
                        'lastName' => 'Company 2',
                        'position' => 'Admin',
                        'role' => 'company-admin',
                        'avatar' => null,
                    ],
                    'contacts' => [
                        'email' => 'admin@company2.com',
                        'phone' => null,
                    ],
                    'meta' => [
                        'status' => 'active',
                        'registeredAt' => '2017-06-13T05:57:21+0000',
                    ],
                ],
                'active' => [
                    [
                        'id' => '9617881b-3ae9-4a7f-82b9-e2f46568f0ca',
                        'profile' => [
                            'name' => 'Employee Company 2',
                            'firstName' => 'Employee',
                            'lastName' => 'Company 2',
                            'position' => 'Employee',
                            'role' => 'employee',
                            'avatar' => null,
                        ],
                        'contacts' => [
                            'email' => 'employee@company2.com',
                            'phone' => null,
                        ],
                        'meta' => [
                            'status' => 'active',
                            'registeredAt' => '2017-06-13T06:02:36+0000',
                        ],
                    ],
                ],
                'deleted' => [],
                'invited' => [
                    [
                        'id' => '7bc366f8-f405-4d23-9c9c-7fff4c68e18:',
                        'contacts' => [
                            'email' => 'invited@company2.com',
                        ],
                        'meta' => [
                            'status' => 'invited',
                            'invitedAt' => '2017-06-13T05:58:12+0000',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
