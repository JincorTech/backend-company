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

        $attrName = trans('legal name');
        $message = trans('validation.required', [
            'attribute' => $attrName,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'legalName' => [
                $message,
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

        $attrName = trans('validation.attributes')['profile.email'];
        $message = trans('validation.email', [
            'attribute' => $attrName,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'profile.email' => [
                $message,
            ],
        ]);
    }

    public function legalNameTooShort(ApiTester $I)
    {
        $I->wantTo('Update my company with too short legal name and receive 422 response code');

        $I->sendPUT('company/my', [
            'legalName' => 'ab',
        ]);

        $attrName = trans('legal name');

        $message = trans('validation.min.string', [
            'attribute' => $attrName,
            'min' => 3,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'legalName' => [
                $message,
            ],
        ]);
    }

    public function descriptionTooLong(ApiTester $I)
    {
        $I->wantTo('Update my company with too long description and receive 422 response code');

        $I->sendPUT('company/my', [
            'profile' => [
                'description' => $I->generateRandomString(851),
            ],
        ]);

        $attrName = trans('validation.attributes')['profile.description'];

        $message = trans('validation.max.string', [
            'attribute' => $attrName,
            'max' => 850,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'profile.description' => [
                $message,
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

        $attrName = trans('validation.attributes')['profile.links.*.value'];
        $message = trans('validation.url', [
            'attribute' => $attrName,
        ]);

        $I->seeResponseCodeIs(422);
        $I->canSeeResponseContainsValidationErrors([
            'profile.links.0.value' => [
                $message,
            ],
        ]);
    }

    public function companyTypeNotFound(ApiTester $I)
    {
        $I->wantTo('Update my company with not existing company type and receive validation error');

        $I->sendPUT('company/my', [
            'profile' => [
                'companyType' => '4f021f7f-23bd-4317-a40b-086bf8e6a98y',
            ],
        ]);

        $message = trans('exceptions.company_type.not_found');

        $I->seeResponseCodeIs(422);
        $I->canSeeResponseContainsValidationErrors([
            'profile.companyType' => [
                $message,
            ],
        ]);
    }

    public function activityTypeNotFound(ApiTester $I)
    {
        $I->wantTo('Update my company with not existing activity type and receive validation error');

        $I->sendPUT('company/my', [
            'profile' => [
                'economicalActivityTypes' => [
                    '4f021f7f-23bd-4317-a40b-086bf8e6a98y',
                ],
            ],
        ]);

        $message = trans('exceptions.economical_activity_type.not_found');

        $I->seeResponseCodeIs(422);
        $I->canSeeResponseContainsValidationErrors([
            'profile.economicalActivityTypes' => [
                $message,
            ],
        ]);
    }

    public function countryNotFound(ApiTester $I)
    {
        $I->wantTo('Update my company with not existing country and receive validation error');

        $I->sendPUT('company/my', [
            'profile' => [
                'address' => [
                    'country' => '4f021f7f-23bd-4317-a40b-086bf8e6a98y',
                ],
            ],
        ]);

        $message = trans('exceptions.country.not_found');

        $I->seeResponseCodeIs(422);
        $I->canSeeResponseContainsValidationErrors([
            'profile.address.country' => [
                $message,
            ],
        ]);
    }

    public function cityNotFound(ApiTester $I)
    {
        $I->wantTo('Update my company with not existing city and receive validation error');

        $I->sendPUT('company/my', [
            'profile' => [
                'address' => [
                    'city' => '4f021f7f-23bd-4317-a40b-086bf8e6a98y',
                ],
            ],
        ]);

        $message = trans('exceptions.city.not_found');

        $I->seeResponseCodeIs(422);
        $I->canSeeResponseContainsValidationErrors([
            'profile.address.city' => [
                $message,
            ],
        ]);
    }

    public function emailNull(ApiTester $I)
    {
        $I->wantTo('Update my company with null email and make sure email was unset');

        $I->sendPUT('company/my', [
            'profile' => [
                'email' => null,
            ],
        ]);

        $I->seeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'profile' => [
                'email' => null,
            ]
        ]);
    }

    public function descriptionNull(ApiTester $I)
    {
        $I->wantTo('Update my company with null description and make sure description was unset');

        $I->sendPUT('company/my', [
            'profile' => [
                'description' => null,
            ],
        ]);

        $I->seeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'profile' => [
                'description' => null,
            ]
        ]);
    }

    public function phoneNull(ApiTester $I)
    {
        $I->wantTo('Update my company with null phone and make sure phone was unset');

        $I->sendPUT('company/my', [
            'profile' => [
                'phone' => null,
            ],
        ]);

        $I->seeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'profile' => [
                'phone' => null,
            ]
        ]);
    }
}
