<?php

use Helper\Api;

class EmployeeMeCest
{
    public function _before(ApiTester $I)
    {
        $token = '123'; //just random token

        $I->amAuthorizedAsTestCompanyAdmin($token);
    }

    public function _after(ApiTester $I)
    {
    }

    public function positionTooLong(ApiTester $I)
    {
        $I->wantTo('Update my profile with too long position (60 chars) and receive 422 response code');
        $I->sendPUT('employee/me', [
            'profile' => [
                'firstName' => 'Ivan',
                'lastName' => 'Ivanov',
                'position' => $I->generateRandomString(61),
            ],
        ]);

        $attrName = trans('validation.attributes')['profile.position'];

        $message = trans('validation.max.string', [
            'attribute' => $attrName,
            'max' => 60,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'profile.position' => [
                $message,
            ],
        ]);
    }

    public function positionTooShort(ApiTester $I)
    {
        $I->wantTo('Update my profile with too short position (1 char) and receive 422 response code');
        $I->sendPUT('employee/me', [
            'profile' => [
                'firstName' => 'Ivan',
                'lastName' => 'Ivanov',
                'position' => 'a',
            ],
        ]);

        $attrName = trans('validation.attributes')['profile.position'];

        $message = trans('validation.min.string', [
            'attribute' => $attrName,
            'min' => 2,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'profile.position' => [
                $message,
            ],
        ]);
    }

    public function firstNameTooShort(ApiTester $I)
    {
        $I->wantTo('Update my profile with too short first name (1 char) and receive 422 response code');
        $I->sendPUT('employee/me', [
            'profile' => [
                'firstName' => 'I',
                'lastName' => 'Ivanov',
                'position' => 'Wizard',
            ],
        ]);

        $attrName = trans('validation.attributes')['profile.firstName'];

        $message = trans('validation.min.string', [
            'attribute' => $attrName,
            'min' => 2,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'profile.firstName' => [
                $message,
            ],
        ]);
    }

    public function lastNameTooShort(ApiTester $I)
    {
        $I->wantTo('Update my profile with too short last name (1 char) and receive 422 response code');
        $I->sendPUT('employee/me', [
            'profile' => [
                'firstName' => 'Ivan',
                'lastName' => 'I',
                'position' => 'Wizard',
            ],
        ]);

        $attrName = trans('validation.attributes')['profile.lastName'];

        $message = trans('validation.min.string', [
            'attribute' => $attrName,
            'min' => 2,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'profile.lastName' => [
                $message,
            ],
        ]);
    }

    public function success(ApiTester $I)
    {
        $profileData = [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'position' => 'Wizard',
        ];

        $I->wantTo('Update my profile with correct data and receive 200 response code.');
        $I->sendPUT('employee/me', [
            'profile' => $profileData,
        ]);

        $I->canSeeResponseCodeIs(200);

        //check that profile data was updated
        $I->canSeeResponseContainsJson($profileData);
    }

    public function emptyFirstName(ApiTester $I)
    {
        $profileData = [
            'firstName' => '',
            'lastName' => 'Ivanov',
            'position' => 'Wizard',
        ];

        $I->wantTo('Update my profile with empty first name and receive validation error.');
        $I->sendPUT('employee/me', [
            'profile' => $profileData,
        ]);

        $attrName = trans('validation.attributes')['profile.firstName'];
        $message = trans('validation.required', [
            'attribute' => $attrName,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'profile.firstName' => [
                $message,
            ],
        ]);
    }

    public function successWithoutFirstName(ApiTester $I)
    {
        $profileData = [
            'lastName' => 'Ivanov',
            'position' => 'Wizard',
        ];

        $I->wantTo('Update my profile without first name and ensure it will not change.');
        $I->sendPUT('employee/me', [
            'profile' => $profileData,
        ]);

        $profileData['firstName'] = 'John';
        $I->canSeeResponseContainsJson($profileData);
    }

    public function emptyLastName(ApiTester $I)
    {
        $profileData = [
            'firstName' => 'Ivan',
            'lastName' => '',
            'position' => 'Wizard',
        ];

        $I->wantTo('Update my profile with empty last name and receive validation error.');
        $I->sendPUT('employee/me', [
            'profile' => $profileData,
        ]);

        $attrName = trans('validation.attributes')['profile.lastName'];
        $message = trans('validation.required', [
            'attribute' => $attrName,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'profile.lastName' => [
                $message,
            ],
        ]);
    }

    public function emptyPosition(ApiTester $I)
    {
        $profileData = [
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'position' => '',
        ];

        $I->wantTo('Update my profile with empty position and receive validation error.');
        $I->sendPUT('employee/me', [
            'profile' => $profileData,
        ]);

        $attrName = trans('validation.attributes')['profile.position'];
        $message = trans('validation.required', [
            'attribute' => $attrName,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'profile.position' => [
                $message,
            ],
        ]);
    }

    public function avatarCorrect(ApiTester $I)
    {
        $url = 'http://url.com'; //just random url
        $I->haveStorageMockForAvatarUpload($url);

        $profileData = [
            'avatar' => Api::base64Png(),
        ];

        $I->wantTo('Update my profile avatar with png image and receive 200 response code.');
        $I->sendPUT('employee/me', [
            'profile' => $profileData,
        ]);

        $I->canSeeResponseCodeIs(200);

        //check that profile data was updated
        $I->canSeeResponseContainsJson([
            'avatar' => $url,
        ]);
    }

    public function avatarJpg(ApiTester $I)
    {
        $profileData = [
            'avatar' => Api::base64Jpg(),
        ];

        $I->wantTo('Update my profile avatar with jpg image and receive 422 response code.');
        $I->sendPUT('employee/me', [
            'profile' => $profileData,
        ]);

        $message = trans('validation.custom')['profile.avatar']['is_png'];

        $I->canSeeResponseContainsValidationErrors([
            'profile.avatar' => [
                $message,
            ],
        ]);
    }

    public function avatarReset(ApiTester $I)
    {
        $profileData = [
            'avatar' => '',
        ];

        $I->wantTo('Remove my avatar and receive 200 response code.');
        $I->sendPUT('employee/me', [
            'profile' => $profileData,
        ]);

        $I->canSeeResponseCodeIs(200);

        $I->canSeeResponseContainsJson([
            'avatar' => null,
        ]);
    }

    public function getProfile(ApiTester $I)
    {
        $I->wantTo('Get my profile data and receive 200 response code');
        $I->sendGET('employee/me');
        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'data' => [
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
        ]);
    }
}
