<?php


class DictionaryCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function country(ApiTester $I)
    {
        $I->wantTo('Get list of available countries to select appropriate country for a company');
        $I->sendGET('dictionary/country');

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContains('{"data":[{"id":"5f2bef92-a8ac-4061-a5c5-5dbabbb328d5","alpha2":"US","name":"\u0421\u0428\u0410","locale":"ru"},{"id":"c699fc1a-ec7f-4021-9102-31ff03c5624a","alpha2":"RU","name":"\u0420\u043e\u0441\u0441\u0438\u044f","locale":"ru"}]}');
    }

    public function city(ApiTester $I)
    {
        $country = 'c699fc1a-ec7f-4021-9102-31ff03c5624a';

        $I->wantTo('Get list of available cities of country to select appropriate city for a company');
        $I->sendGET('dictionary/city', [
            'country' => $country,
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseJsonMatchesJsonPath('$.data[*].id');
        $I->canSeeResponseJsonMatchesJsonPath('$.data[*].name');
    }
}
