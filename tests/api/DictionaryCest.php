<?php

/**
 * Class DictionaryCest.
 *
 * Test cases for testing dictionaries and classifiers
 */
class DictionaryCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Dictionary\Http\Controllers\ActivityTypeController::getList
     */
    public function activityTypeListTest(ApiTester $I)
    {
        $I->sendGET('dictionary/activityType');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    /**
     * Activity type request validate requests.
     *
     * @param ApiTester $I
     * @covers \App\Applications\Dictionary\Http\Controllers\ActivityTypeController::getList
     */
    public function activityTypeListValidatesRequest(ApiTester $I)
    {
        $I->sendGET('dictionary/activityType?name=ru');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'name' => [
                    'The name must be at least 3 characters.',
                ],
            ],
            'status_code' => 400,
        ]);

        $I->sendGET('dictionary/activityType?locale=english');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'locale' => [
                    'The locale must be 2 characters.',
                ],
            ],
            'status_code' => 400,
        ]);

        $I->sendGET('dictionary/activityType?code=r');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'code' => [
                    'The code must be at least 2 characters.',
                ],
            ],
            'status_code' => 400,
        ]);

        $I->sendGET('dictionary/activityType?code=rusddkkqasds');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'code' => [
                    'The code may not be greater than 6 characters.',
                ],
            ],
            'status_code' => 400,
        ]);

        $I->sendGET('dictionary/activityType?goodCodes=r');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'goodCodes' => [
                    'The good codes must be at least 2 characters.',
                ],
            ],
            'status_code' => 400,
        ]);
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Dictionary\Http\Controllers\GoodTypeController::getList
     */
    public function goodsListTest(ApiTester $I)
    {
        $I->sendGET('dictionary/goods');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    public function goodsListValidatesRequest(ApiTester $I)
    {
        $I->sendGET('dictionary/goods?name=ru');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'name' => [
                    'The name must be at least 3 characters.',
                ],
            ],
            'status_code' => 400,
        ]);

        $I->sendGET('dictionary/goods?locale=english');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'locale' => [
                    'The locale must be 2 characters.',
                ],
            ],
            'status_code' => 400,
        ]);

        $I->sendGET('dictionary/goods?code=r');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'code' => [
                    'The code must be at least 2 characters.',
                ],
            ],
            'status_code' => 400,
        ]);

        $I->sendGET('dictionary/goods?code=rusddkkqasds');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'code' => [
                    'The code may not be greater than 6 characters.',
                ],
            ],
            'status_code' => 400,
        ]);

        $I->sendGET('dictionary/goods?activityCodes=r');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'activityCodes' => [
                    'The activity codes must be at least 2 characters.',
                ],
            ],
            'status_code' => 400,
        ]);
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Dictionary\Http\Controllers\DictionaryController::listCountries
     */
    public function countryListTest(ApiTester $I)
    {
        $I->sendGET('dictionary/country');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    /**
     * Test if route return errors.
     *
     * @param ApiTester $I
     * @covers \App\Applications\Dictionary\Http\Controllers\DictionaryController::listCountries
     */
    public function countryListValidatesRequest(ApiTester $I)
    {
        $I->sendGET('dictionary/country?alpha2=rus');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'alpha2' => [
                    'The alpha2 must be 2 characters.',
                ],
            ],
            'status_code' => 400,
        ]);

        $I->sendGET('dictionary/country?name=ru');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'name' => [
                    'The name must be at least 3 characters.',
                ],
            ],
            'status_code' => 400,
        ]);
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Dictionary\Http\Controllers\DictionaryController::listAdministrativeAreasByCountry
     */
    public function countryAreasTest(ApiTester $I)
    {
        $I->sendGET('dictionary/country/1/area');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Dictionary\Http\Controllers\DictionaryController::listPhoneCodes
     */
    public function phoneCodeTest(ApiTester $I)
    {
        $I->sendGET('dictionary/phoneCode');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    /**
     * Test if route returns errors.
     *
     * @param ApiTester $I
     * @covers \App\Applications\Dictionary\Http\Controllers\DictionaryController::listPhoneCodes
     */
    public function phoneCodeValidatesRequest(ApiTester $I)
    {
        $I->sendGET('dictionary/phoneCode?countryId=66343963-3331-6432-2d36-3231612d346');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
                'message' => '400 Bad Request',
                'errors' => [
                    'countryId' => [
                        'The country id must be 36 characters.',
                    ],
                ],
                'status_code' => 400,
            ]);

        $I->sendGET('dictionary/phoneCode?alpha2=rus');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'alpha2' => [
                    'The alpha2 must be 2 characters.',
                ],
            ],
            'status_code' => 400,
        ]);
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Dictionary\Http\Controllers\DictionaryController::listCurrencies
     */
    public function currencyTest(ApiTester $I)
    {
        $I->sendGET('dictionary/currency');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    /**
     * Test if route returns errors.
     *
     * @param ApiTester $I
     * @covers \App\Applications\Dictionary\Http\Controllers\DictionaryController::listCurrencies
     */
    public function currencyValidatesRequest(ApiTester $I)
    {
        $I->sendGET('dictionary/currency?countryId=66343963-3331-6432-2d36-3231612d346');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'countryId' => [
                    'The country id must be 36 characters.',
                ],
            ],
            'status_code' => 400,
        ]);
        $I->sendGET('dictionary/currency?alpha3=ru');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '400 Bad Request',
            'errors' => [
                'alpha3' => [
                    'The alpha3 must be 3 characters.',
                ],
            ],
            'status_code' => 400,
        ]);
    }
}
