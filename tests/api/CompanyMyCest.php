<?php


class CompanyMyCest
{
    public function _before(ApiTester $I)
    {
        $token = '123'; //just random token
        $I->amAuthorizedAsTestCompanyAdmin($token);
    }

    public function _after(ApiTester $I)
    {
    }

    public function updateSuccess(ApiTester $I)
    {
        $I->wantTo('Update my company as company admin and receive success response');

        $I->sendPUT('company/my', [
            'legalName' => 'New Company Name',
            'profile' => [
                'brandName' => [
                    'en' => 'My english brand name',
                    'ru' => 'Мое русское брендовое имя!',
                ],
                'links' => [
                    [
                        'name' => 'facebook',
                        'value' => 'http://facebook.com',
                    ],
                ],
                'description' => 'New description',
                'email' => 'admin@jincor.com',
                'phone' => '+7999229393',
                'address' => [
                    'country' => 'c699fc1a-ec7f-4021-9102-31ff03c5624a',
                    'city' => '46d52498-065c-4c95-a5b6-aba3e43bc8ce',
                    'formattedAddress' => 'Пироговый переулок, 5, оф. 15',
                ],
                'economicalActivityTypes' => [
                    '16f02a56-1723-47a3-88ea-daaa648d331d',
                    'ee6ed89c-fca9-40a7-9187-fe881690aa52',
                ],
                'companyType' => '4f021f7f-23bd-4317-a40b-086bf8e6a98d',
                'picture' => '',
            ],
        ]);

        $I->seeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'data' => [
                'id' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
                'legalName' => 'New Company Name',
                'profile' => [
                    'brandName' => [
                        'en' => 'My english brand name',
                        'ru' => 'Мое русское брендовое имя!',
                    ],
                    'description' => 'New description',
                    'picture' => null,
                    'links' => [
                        [
                            'name' => 'facebook.com',
                            'value' => 'http://facebook.com',
                        ],
                    ],
                    'email' => 'admin@jincor.com',
                    'phone' => '+7999229393',
                    'address' => [
                        'country' => [
                            'id' => 'c699fc1a-ec7f-4021-9102-31ff03c5624a',
                            'name' => 'Россия',
                        ],
                        'city' => [
                            'id' => '46d52498-065c-4c95-a5b6-aba3e43bc8ce',
                            'name' => 'Домодедово',
                        ],
                        'formattedAddress' => 'Пироговый переулок, 5, оф. 15',
                    ],
                ],
                'economicalActivityTypes' => [
                    [
                        'id' => '16f02a56-1723-47a3-88ea-daaa648d331d',
                        'name' => 'Сельское хозяйство и лесозаготовка',
                        'code' => 'A',
                    ],
                    [
                        'id' => 'ee6ed89c-fca9-40a7-9187-fe881690aa52',
                        'name' => 'Разведение, генетика и селекция',
                        'code' => 'AF',
                    ],
                ],
                'companyType' => [
                    'id' => '4f021f7f-23bd-4317-a40b-086bf8e6a98d',
                    'name' => 'Частная компания',
                    'code' => 'BT1',
                ],
                'employeesCount' => 2,
            ],
        ]);
    }

    public function emptyLegalName(ApiTester $I)
    {
        $I->wantTo('Update my company with empty legal name and receive validation error');

        $I->sendPUT('company/my', [
            'legalName' => '',
            'profile' => [
                'brandName' => [
                    'en' => 'My english brand name',
                    'ru' => 'Мое русское брендовое имя!',
                ],
            ],
        ]);

        $I->canSeeResponseContainsValidationErrors([
           'legalName' => [
               'The legal name field is required.',
           ],
        ]);
    }

    public function withoutLegalName(ApiTester $I)
    {
        $I->wantTo('Update my company without legal name and ensure it will not change');

        $I->sendPUT('company/my', [
            'profile' => [
                'brandName' => [
                    'en' => 'My english brand name',
                    'ru' => 'Мое русское брендовое имя!',
                ],
            ],
        ]);

        $I->seeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'legalName' => 'Test Company',
        ]);
    }

    public function emailNotValid(ApiTester $I)
    {
        $I->wantTo('Update my company with incorrect email and receive 422 response code');

        $I->sendPUT('company/my', [
            'profile' => [
                'email' => 'admin.jincor.com',
            ],
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'profile.email' => [
                'The email must be a valid email address.',
            ],
        ]);
    }

    public function legalNameTooShort(ApiTester $I)
    {
        $I->wantTo('Update my company with too short legal name and receive 422 response code');

        $I->sendPUT('company/my', [
            'legalName' => 'ab',
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'legalName' => [
                'The legal name must be at least 3 characters.'
            ],
        ]);
    }

    public function descriptionTooLong(ApiTester $I)
    {
        $I->wantTo('Update my company with too long description and receive 422 response code');

        $I->sendPUT('company/my', [
            'profile' => [
                'description' => $I->generateRandomString(551),
            ],
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'profile.description' => [
                'The description may not be greater than 550 characters.'
            ],
        ]);
    }

    public function linksNotValidUrl(ApiTester $I)
    {
        $I->wantTo('Update my company with not valid url link and receive 422 response code');

        $I->sendPUT('company/my', [
            'profile' => [
                'links' => [
                    [
                        'name' => 'name',
                        'value' => 'something random',
                    ],
                ],
            ],
        ]);

        //TODO: Fix this, backend must return 422 code
        $I->seeResponseCodeIs(500);
        $I->canSeeResponseIsJson();
    }
}
