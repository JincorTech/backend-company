<?php

use App\Core\Services\Verification\DummyVerificationService;
use App\Core\Services\Verification\VerificationService;

class CompanyApiCest
{
    public function _before(ApiTester $I)
    {
        $I->haveBinding(VerificationService::class, DummyVerificationService::class);

        Elasticsearch::shouldReceive('index')
            ->once()
            ->andReturn(null);
    }

    public function _after(ApiTester $I)
    {
    }

    public function registerSuccess(ApiTester $I)
    {
        $I->wantTo('Register new company in existing country and with existing company type.
         And get verification ID as a response');

        $countryId = 'c699fc1a-ec7f-4021-9102-31ff03c5624a';
        $companyType = '4f021f7f-23bd-4317-a40b-086bf8e6a98d';

        $I->sendPOST('company', [
            'legalName' => 'Рога и Копыта',
            'countryId' => $countryId,
            'companyType' => $companyType,
        ]);

        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('data.id');
        $I->seeResponseJsonMatchesJsonPath('data.companyId');
        $I->seeResponseJsonMatchesJsonPath('data.email');
        $I->seeResponseJsonMatchesJsonPath('data.email.value');
        $I->seeResponseJsonMatchesJsonPath('data.email.isVerified');
        $I->seeResponseJsonMatchesJsonPath('data.phone.isVerified');
        $I->seeResponseJsonMatchesJsonPath('data.phone.value');

        $company = $I->grabDataFromResponseByJsonPath('$.data.companyId.');
        $I->seeHttpHeader('Location', '/api/v1/company/' . $company[0]);
    }

    public function registerIncorrectCountry(ApiTester $I)
    {
        $I->wantTo('Register new company with incorrect country id and receive validation error');

        $companyType = '4f021f7f-23bd-4317-a40b-086bf8e6a98d';

        $I->sendPOST('company', [
            'legalName' => 'Рога и Копыта',
            'countryId' => $companyType,
            'companyType' => $companyType,
        ]);

        $message = trans('registration.countryNotFound', [
            'country' => $companyType,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'country' => [
                $message,
            ],
        ]);
    }

    public function registerIncorrectType(ApiTester $I)
    {
        $I->wantTo('Register new company with incorrect type and receive validation error');

        $countryId = 'c699fc1a-ec7f-4021-9102-31ff03c5624a';;

        $I->sendPOST('company', [
            'legalName' => 'Рога и Копыта',
            'countryId' => $countryId,
            'companyType' => $countryId,
        ]);

        $message = trans('registration.typeNotFound', [
            'ct' => $countryId,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'companyType' => [
                $message,
            ],
        ]);
    }

    public function getCompanyTypes(ApiTester $I)
    {
        $I->wantTo('Get available company types in order to select appropriate type for my company');

        $I->sendGET('company/types');

        $I->seeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContains('{"data":[{"id":"4f021f7f-23bd-4317-a40b-086bf8e6a98d","name":"\u0427\u0430\u0441\u0442\u043d\u0430\u044f \u043a\u043e\u043c\u043f\u0430\u043d\u0438\u044f","code":"BT1"},{"id":"23e1facd-178d-48e5-998a-9c2a6a181077","name":"\u041f\u0443\u0431\u043b\u0438\u0447\u043d\u0430\u044f \u043a\u043e\u043c\u043f\u0430\u043d\u0438\u044f","code":"BT2"},{"id":"6cbcb2fb-50fc-4610-b836-b421d100af02","name":"\u0418\u043d\u0434\u0438\u0432\u0438\u0434\u0443\u0430\u043b\u044c\u043d\u044b\u0439 \u043f\u0440\u0435\u0434\u043f\u0440\u0438\u043d\u0438\u043c\u0430\u0442\u0435\u043b\u044c","code":"BT3"},{"id":"9ef0d564-30a4-40ae-b5e0-172eeda90b24","name":"\u041d\u0435\u043a\u043e\u043c\u043c\u0435\u0440\u0447\u0435\u0441\u043a\u0430\u044f \u043e\u0440\u0433\u0430\u043d\u0438\u0437\u0430\u0446\u0438\u044f","code":"BT4"},{"id":"443a21f6-ca48-46fa-8ede-7135afff4a13","name":"\u0413\u043e\u0441\u0443\u0434\u0430\u0440\u0441\u0442\u0432\u0435\u043d\u043d\u043e\u0435 \u043f\u0440\u0435\u0434\u043f\u0440\u0438\u044f\u0442\u0438\u0435","code":"BT5"},{"id":"fc5282d9-4912-480a-b482-d9ca90a125c0","name":"\u0413\u043e\u0441\u0443\u0434\u0430\u0440\u0441\u0442\u0432\u0435\u043d\u043d\u043e\u0435 \u043f\u0440\u0435\u0434\u043f\u0440\u0438\u044f\u0442\u0438\u0435","code":"BT6"},{"id":"1498aa89-118a-48e4-813e-b9696f977ce7","name":"\u041c\u0435\u0436\u0434\u0443\u043d\u0430\u0440\u043e\u0434\u043d\u0430\u044f \u043e\u0440\u0433\u0430\u043d\u0438\u0437\u0430\u0446\u0438\u044f","code":"BT7"}]}');
    }

    public function getActivityTypes(ApiTester $I)
    {
        $I->wantTo('Get available activity types in order to select appropriate type for my company');

        $I->sendGET('company/activityTypes');

        $I->seeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseJsonMatchesJsonPath('$.data[*].id');
        $I->canSeeResponseJsonMatchesJsonPath('$.data[*].name');
        $I->canSeeResponseJsonMatchesJsonPath('$.data[*].code');
        $I->canSeeResponseJsonMatchesJsonPath('$.data[*].children');
    }

    public function inviteEmployees(ApiTester $I)
    {
        $token = '123'; //just random token

        $I->amAuthorizedAsJincorAdmin($token);

        $I->wantTo('Invite an employee to my company to be able to contact him in Jincor messenger');
        $I->amBearerAuthenticated($token);

        $I->sendPOST('company/invite', [
            'emails' => [
                'hlogeon1@gmail.com',
                'ortgma@gmail.com',
            ],
        ]);

        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseJsonMatchesJsonPath('$.data[*].status');
        $I->canSeeResponseJsonMatchesJsonPath('$.data[*].verification');
        $I->canSeeResponseJsonMatchesJsonPath('$.data[*].verification.verificationId');
    }

    public function companyInfoById(ApiTester $I)
    {
        $companyId = '9fcad7c5-f84e-4d43-b35c-05e69d0e0362';

        $I->wantTo('Get company info by id');

        $I->sendGET('company/' . $companyId);

        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContains('{"data":{"id":"9fcad7c5-f84e-4d43-b35c-05e69d0e0362","legalName":"Test Company","profile":{"brandName":null,"description":null,"picture":null,"links":[],"email":null,"phone":null,"address":{"country":{"id":"c699fc1a-ec7f-4021-9102-31ff03c5624a","name":"\u0420\u043e\u0441\u0441\u0438\u044f"},"city":null,"formattedAddress":"\u041c\u043e\u0441\u043a\u0432\u0430, \u0443\u043b. \u0410\u043b\u0430\u044f, \u0434. 15, \u043e\u0444. 89, 602030"}},"economicalActivityTypes":[],"companyType":{"id":"4f021f7f-23bd-4317-a40b-086bf8e6a98d","name":"\u0427\u0430\u0441\u0442\u043d\u0430\u044f \u043a\u043e\u043c\u043f\u0430\u043d\u0438\u044f","code":"BT1"},"employeesCount":3}}');
    }

    public function companySearchByCountry(ApiTester $I)
    {
        $elasticResults = [
            'hits' => [
                'hits' => [
                    [
                        '_id' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362'
                    ],
                    [
                        '_id' => '06c52e3c-9366-4b9d-9da2-265613adb72e'
                    ],
                ],
            ],
        ];

        Elasticsearch::shouldReceive('search')
            ->once()
            ->andReturn($elasticResults);

        $country = 'c699fc1a-ec7f-4021-9102-31ff03c5624a';

        $I->wantTo('Search companies by country and get second page with 2 companies per page');

        $I->sendGET('company/search', [
            'country' => $country,
            'page' => 1,
            'perPage' => 2,
        ]);

        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'data' => [
                [
                    'id' => '06c52e3c-9366-4b9d-9da2-265613adb72e',
                    'legalName' => 'Jincor Limited',
                    'profile' => [
                        'brandName' => null,
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
                    'employeesCount' => 0,
                ],
                [
                    'id' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
                    'legalName' => 'Test Company',
                    'profile' => [
                        'brandName' => null,
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
                    'employeesCount' => 3,
                ],
            ],
            'meta' => [
                'pagination' => [
                    'total' => 2,
                    'perPage' => 2,
                    'currentPage' => 1,
                    'lastPage' => 1,
                    'nextPageUrl' => null,
                    'prevPageUrl' => null,
                    'from' => 1,
                    'to' => 2,
                ],
            ],
        ]);
    }

}
