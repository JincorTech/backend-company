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


    public function getListOfEmployeesByMatrixIds1Found(ApiTester $I)
    {
        $I->wantTo('Get the list of employees by providing an array of MatrixIDs');
        $I->sendPOST('employee/matrix', [
            'matrixIds' => [
                '@9fcad7c5-f84e-4d43-b35c-05e69d0e0362_test_sdjshldakd.com', //not existing
                '@9fcad7c5-f84e-4d43-b35c-05e69d0e0362_test2_test.com', //existing
            ],
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'data' => [
                [
                    'id' => '63e88d9d-a79e-4705-9c8f-8712b71b53f8',
                    'email' => 'test2@test.com',
                    'name' => 'John Doe',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'avatar' => 'http://existing2.avatar',
                    'position' => 'Tester',
                    'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
                    'companyName' => 'Test Company',
                    'companyLogo' => null,
                ],
            ],
        ]);
    }

    public function getListOfEmployeesByMatrixIds2Found(ApiTester $I)
    {
        $I->wantTo('Get the list of employees by providing an array of MatrixIDs');
        $I->sendPOST('employee/matrix', [
            'matrixIds' => [
                '@06c52e3c-9366-4b9d-9da2-265613adb72e_test_test.com',
                '@9fcad7c5-f84e-4d43-b35c-05e69d0e0362_test2_test.com',
            ],
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'data' => [
                [
                    'id' => base64_decode('OTg2ODdlMjktZjA1OS00YmFhLTk0NjktMTkwNzZiYmM0YzBh'),
                    'email' => 'test@test.com',
                    'name' => 'John Doe',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'avatar' => 'http://existing.avatar',
                    'position' => 'Tester',
                    'companyId' => '06c52e3c-9366-4b9d-9da2-265613adb72e',
                    'companyName' => 'Jincor Limited',
                    'companyLogo' => null,
                ],
                [
                    'id' => '63e88d9d-a79e-4705-9c8f-8712b71b53f8',
                    'email' => 'test2@test.com',
                    'name' => 'John Doe',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'avatar' => 'http://existing2.avatar',
                    'position' => 'Tester',
                    'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
                    'companyName' => 'Test Company',
                    'companyLogo' => null,
                ],
            ],
        ]);
    }

    public function getListOfEmployeesNotExistingMatrixIds(ApiTester $I)
    {
        $I->wantTo('Get the list of employees with not existing matrix IDs and receive empty data');
        $I->sendPOST('employee/matrix', [
            'matrixIds' => [
                '@9fcad7c5-f84e-4d43-b35c-05e69d0e0362_test_sdjshldakd', //not existing
                '@9fcad7c5-f84e-4d43-b35c-05e69d0e0362_test_sdjshldaad', //not existing
            ],
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseEquals('{"data":[]}');
    }

    public function getListOfEmployeesByNotArrayMatrixIds(ApiTester $I)
    {
        $I->wantTo('Get the list of employees with not array matrix IDs and receive validation error');
        $I->sendPOST('employee/matrix', [
            'matrixIds' => '123',
        ]);

        $message = trans('validation.array', [
            'attribute' => trans('matrix ids'),
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'matrixIds' => [
                $message
            ],
        ]);
    }

    public function getListOfEmployeesByEmptyStringMatrixIds(ApiTester $I)
    {
        $I->wantTo('Get the list of employees with empty string matrix IDs and receive validation error');
        $I->sendPOST('employee/matrix', [
            'matrixIds' => '',
        ]);

        $message = trans('validation.required', [
            'attribute' => trans('matrix ids'),
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'matrixIds' => [
                $message
            ],
        ]);
    }

    public function getListOfEmployeesByInvalidMatrixIds(ApiTester $I)
    {
        $I->wantTo('Get the list of employees with invalid matrix IDs format and receive validation error');
        $I->sendPOST('employee/matrix', [
            'matrixIds' => [
                '@9fcad7c5-f84e-4d43-b35c-05e69d0e0362_testsdjshldaad'
            ],
        ]);

        $attrName = trans('validation.attributes')['matrixIds.*'];

        $message = trans('validation.regex', [
            'attribute' => $attrName,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'matrixIds.0' => [
                $message
            ],
        ]);
    }


}
