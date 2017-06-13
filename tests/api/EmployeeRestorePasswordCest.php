<?php


class EmployeeRestorePasswordCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function success(ApiTester $I)
    {
        $I->wantTo('Send a request to restore password with existing email to receive password restore link.');
        $I->sendPOST('employee/restorePassword', [
            'email' => 'test@test.com',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'data' => [
                'companyId' => null,
                'email' => [
                    'value' => 'test@test.com',
                    'isVerified' => false
                ],
                'phone' => [
                    'value' => null,
                    'isVerified' => false
                ],
            ],
        ]);
    }

    public function emailNotExist(ApiTester $I)
    {
        $I->wantTo('Send a request to restore password with not existing email and receive 404 response code.');
        $I->sendPOST('employee/restorePassword', [
            'email' => 'randomstuff@test.com',
        ]);

        $I->canSeeResponseCodeIs(404);
        $I->canSeeResponseIsJson();

        //TODO: check why this response is in Russian by default
        $I->canSeeResponseContainsJson([
            'message' => 'Сотрудник с e-mail адресом randomstuff@test.com ненайден на Jincor',
            'status_code' => 404,
        ]);
    }
}
