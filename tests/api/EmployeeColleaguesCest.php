<?php


class EmployeeColleaguesCest
{
    public function _before(ApiTester $I)
    {
        $token = '123'; //just random token
        $I->amAuthorizedAsTestCompanyAdmin($token);
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function getColleagues(ApiTester $I)
    {
        $I->wantTo('Get list of my colleagues and receive success response');
        $I->sendGET('employee/colleagues');

    }
}
