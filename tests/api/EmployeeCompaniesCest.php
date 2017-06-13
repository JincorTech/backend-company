<?php


class EmployeeCompaniesCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function getCompaniesByVerificationId(ApiTester $I)
    {
        $verificationId = '3e497dd3-4d2f-4eee-84ce-02516982b1ff';

        $I->wantTo('Get avaiable companies list by valid verification ID and receive success response with companies data');
        $I->sendGET('employee/companies', [
            'verificationId' => $verificationId,
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseEquals('{"data":[{"id":"9fcad7c5-f84e-4d43-b35c-05e69d0e0362","legalName":"Test Company","profile":{"brandName":null,"description":null,"picture":null,"links":[],"email":null,"phone":null,"address":{"country":{"id":"c699fc1a-ec7f-4021-9102-31ff03c5624a","name":"\u0420\u043e\u0441\u0441\u0438\u044f"},"city":null,"formattedAddress":"\u041c\u043e\u0441\u043a\u0432\u0430, \u0443\u043b. \u0410\u043b\u0430\u044f, \u0434. 15, \u043e\u0444. 89, 602030"}},"economicalActivityTypes":[],"companyType":{"id":"4f021f7f-23bd-4317-a40b-086bf8e6a98d","name":"\u0427\u0430\u0441\u0442\u043d\u0430\u044f \u043a\u043e\u043c\u043f\u0430\u043d\u0438\u044f","code":"BT1"},"employeesCount":2}]}');
    }

    public function getCompaniesByLoginAndPassword(ApiTester $I)
    {
        $I->wantTo('Get avaiable companies list by correct login and password and receive success response with companies data');
        $I->sendGET('employee/companies', [
            'email' => 'test2@test.com',
            'password' => 'Cm3jpmrt7c',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseEquals('{"data":[{"id":"9fcad7c5-f84e-4d43-b35c-05e69d0e0362","legalName":"Test Company","profile":{"brandName":null,"description":null,"picture":null,"links":[],"email":null,"phone":null,"address":{"country":{"id":"c699fc1a-ec7f-4021-9102-31ff03c5624a","name":"\u0420\u043e\u0441\u0441\u0438\u044f"},"city":null,"formattedAddress":"\u041c\u043e\u0441\u043a\u0432\u0430, \u0443\u043b. \u0410\u043b\u0430\u044f, \u0434. 15, \u043e\u0444. 89, 602030"}},"economicalActivityTypes":[],"companyType":{"id":"4f021f7f-23bd-4317-a40b-086bf8e6a98d","name":"\u0427\u0430\u0441\u0442\u043d\u0430\u044f \u043a\u043e\u043c\u043f\u0430\u043d\u0438\u044f","code":"BT1"},"employeesCount":2}]}');
    }

    public function getCompaniesByIncorrectLoginAndPassword(ApiTester $I)
    {
        $I->wantTo('Get avaiable companies list by incorrect login and password and receive empty companies data');
        $I->sendGET('employee/companies', [
            'email' => 'test2@test.com',
            'password' => 'Cm3jpmrt7c1',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'data' => [],
        ]);
    }
}
