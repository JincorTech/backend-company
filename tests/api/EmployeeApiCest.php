<?php


class EmployeeApiCest
{
    public function _before(ApiTester $I)
    {
        $token = '123'; //just random token
        $I->amAuthorizedAsJincorEmployee($token);
    }

    public function _after(ApiTester $I)
    {
    }


    public function getListOfEmployeesByMatrixIds(ApiTester $I)
    {
        $I->wantTo("Get the list of employees by providing an array of MatrixIDs");
        $I->sendPOST('employee/matrix', [
            'matrixIds' => [
                "@9fcad7c5-f84e-4d43-b35c-05e69d0e0362_testsdjshldakd",
                "@9fcad7c5-f84e-4d43-b35c-05e69d0e0362_test2_test.com",
            ]
        ]);
        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
//        TODO: seed data to testing database and match response!
//        $I->canSeeResponseJsonMatchesJsonPath('data.*.id');

    }

//    TODO: Check for error and validation responses

}
