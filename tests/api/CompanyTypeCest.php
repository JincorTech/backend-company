<?php


class CompanyTypeCest
{
    public function getList(ApiTester $I)
    {
        $I->wantTo('Get the list of available company types');
        $I->sendGET('company/types');
        $I->seeResponseIsJson();
        $I->canSeeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('data[1].id');
    }
}
