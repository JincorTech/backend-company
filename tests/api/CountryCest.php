<?php


class CountryCest
{
    public function _before(ApiTester $I)
    {

    }

    public function _after(ApiTester $I)
    {
    }

    /**
     * @param $I ApiTester
     */
    public function getList(ApiTester $I)
    {
        $I->wantTo('Get the list of all countries');

        $I->sendGET('dictionary/country');
        $I->seeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseJsonMatchesJsonPath('data[1].id');
    }
}
