<?php


class EmployeeContactsCest
{
    public function _before(ApiTester $I)
    {
        $I->haveWalletsMock();
    }

    public function _after(ApiTester $I)
    {
    }

    public function getContacts(ApiTester $I)
    {
        $I->amAuthorizedAsJincorAdmin('123');
        $I->wantTo('Get my contact list');
        $I->sendGET('employee/contacts');

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'data' => [
                [
                    'id' => '9617881b-3ae9-4a7f-82b9-e2f46568f0ca',
                    'email' => 'employee@company2.com',
                    'name' => 'Employee Company 2',
                    'firstName' => 'Employee',
                    'lastName' => 'Company 2',
                    'avatar' => null,
                    'position' => 'Employee',
                    'companyId' => '8d80a3e9-515d-4974-927d-4b097d1eb9fe',
                    'companyName' => 'Jincor',
                    'companyLogo' => null,
                    'matrixId' => '@8d80a3e9-515d-4974-927d-4b097d1eb9fe_employee_company2.com',
                ],
            ],
            'meta' => [
                'pagination' => [
                    'total' => 1,
                    'perPage' => 10,
                    'currentPage' => 1,
                    'lastPage' => 1,
                    'nextPageUrl' => null,
                    'prevPageUrl' => null,
                    'from' => 1,
                    'to' => 1,
                ],
            ],
        ]);
    }

    public function getContactsPerPage0(ApiTester $I)
    {
        $I->amAuthorizedAsJincorAdmin('123');
        $I->wantTo('Get my contact list with perPage = 0 and get all available contacts');
        $I->sendGET('employee/contacts', [
            'perPage' => 0,
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'data' => [
                [
                    'id' => '9617881b-3ae9-4a7f-82b9-e2f46568f0ca',
                    'email' => 'employee@company2.com',
                    'name' => 'Employee Company 2',
                    'firstName' => 'Employee',
                    'lastName' => 'Company 2',
                    'avatar' => null,
                    'position' => 'Employee',
                    'companyId' => '8d80a3e9-515d-4974-927d-4b097d1eb9fe',
                    'companyName' => 'Jincor',
                    'companyLogo' => null,
                    'matrixId' => '@8d80a3e9-515d-4974-927d-4b097d1eb9fe_employee_company2.com',
                ],
            ],
            'meta' => [
                'pagination' => [
                    'total' => 1,
                    'perPage' => 1,
                    'currentPage' => 1,
                    'lastPage' => 1,
                    'nextPageUrl' => null,
                    'prevPageUrl' => null,
                    'from' => 1,
                    'to' => 1,
                ],
            ],
        ]);
    }

    public function addContactSuccess(ApiTester $I)
    {
        $I->amAuthorizedAsJincorAdmin('123');
        $I->wantTo('Add new contact to my contact list and receive updated contact list as response');
        $I->sendPOST('employee/contacts', [
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
            'email' => 'test2@test.com',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'data' => [
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
                'matrixId' => '@9fcad7c5-f84e-4d43-b35c-05e69d0e0362_test2_test.com',
            ],
        ]);
    }

    public function addContactCompanyNotExist(ApiTester $I)
    {
        $I->amAuthorizedAsJincorAdmin('123');
        $I->wantTo('Add new contact to my contact list from not existing company and receive not found error');
        $I->sendPOST('employee/contacts', [
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0363',
            'email' => 'test2@test.com',
        ]);

        $I->canSeeResponseCodeIs(404);
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.company.not_found'),
        ]);
    }

    public function addContactEmailNotExist(ApiTester $I)
    {
        $I->amAuthorizedAsJincorAdmin('123');
        $I->wantTo('Add new contact to my contact list with not existing email and receive not found error');
        $I->sendPOST('employee/contacts', [
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
            'email' => 'notexist@test.com',
        ]);

        $message = trans('exceptions.employee.not_found', [
            'email' => 'notexist@test.com',
        ]);
        $I->canSeeResponseCodeIs(404);
        $I->canSeeResponseContainsJson([
            'message' => $message,
        ]);
    }

    public function addContactWithoutEmail(ApiTester $I)
    {
        $I->amAuthorizedAsJincorAdmin('123');
        $I->wantTo('Add new contact to my contact list without email and receive validation error');
        $I->sendPOST('employee/contacts', [
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
        ]);

        $message = trans('validation.required', [
            'attribute' => trans('email'),
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'email' => [
                $message
            ],
        ]);
    }

    public function addContactInvalidEmail(ApiTester $I)
    {
        $I->amAuthorizedAsJincorAdmin('123');
        $I->wantTo('Add new contact to my contact list with invalid email and receive validation error');
        $I->sendPOST('employee/contacts', [
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
            'email' => 'invalid.email.com',
        ]);

        $message = trans('validation.email', [
            'attribute' => trans('email'),
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'email' => [
                $message
            ],
        ]);
    }

    public function deleteContactSuccess(ApiTester $I)
    {
        $I->amAuthorizedAsJincorAdmin('123');
        $I->wantTo('Delete a contact from contact list and receive updated contact list as response');
        $I->sendDELETE('employee/contacts/9617881b-3ae9-4a7f-82b9-e2f46568f0ca');

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'data' => [
                'id' => '9617881b-3ae9-4a7f-82b9-e2f46568f0ca',
                'email' => 'employee@company2.com',
                'name' => 'Employee Company 2',
                'firstName' => 'Employee',
                'lastName' => 'Company 2',
                'avatar' => null,
                'position' => 'Employee',
                'companyId' => '8d80a3e9-515d-4974-927d-4b097d1eb9fe',
                'companyName' => 'Jincor',
                'companyLogo' => null,
                'matrixId' => '@8d80a3e9-515d-4974-927d-4b097d1eb9fe_employee_company2.com',
            ],
        ]);
    }

    public function deleteContactNotExistingEmployee(ApiTester $I)
    {
        $I->amAuthorizedAsJincorAdmin('123');
        $I->wantTo('Delete a contact with not existing employee id and receive 404 response');
        $I->sendDELETE('employee/contacts/9617881b-3ae9-4a7f-82b9-e2f46568f0c1');

        $message = trans('exceptions.employee.not_found_id', [
            'id' => '9617881b-3ae9-4a7f-82b9-e2f46568f0c1',
        ]);
        $I->canSeeResponseCodeIs(404);
        $I->canSeeResponseContainsJson([
            'message' => $message,
        ]);
    }

    public function searchNotAddedEmployee(ApiTester $I)
    {
        $I->amAuthorizedAsJincorAdmin('123');
        $I->wantTo('Search for new contacts by email and find existing employee which is not added to my contact list');
        $I->sendGET('employee/contacts/search', [
            'email' => 'test2@test.com',
        ]);

        $I->canSeeResponseCodeIs(200);
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
                    'added' => false,
                    'matrixId' => '@9fcad7c5-f84e-4d43-b35c-05e69d0e0362_test2_test.com',

                ],
            ],
            'meta' => [
                'pagination' => [
                    'total' => 1,
                    'perPage' => 10,
                    'currentPage' => 1,
                    'lastPage' => 1,
                    'nextPageUrl' => null,
                    'prevPageUrl' => null,
                    'from' => 1,
                    'to' => 1,
                ],
            ],
        ]);
    }

    public function searchAddedEmployee(ApiTester $I)
    {
        $I->amAuthorizedAsJincorAdmin('123');
        $I->wantTo('Search for new contacts by email and find existing employee which is added to my contact list');
        $I->sendGET('employee/contacts/search', [
            'email' => 'employee@company2.com',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'data' => [
                [
                    'id' => '9617881b-3ae9-4a7f-82b9-e2f46568f0ca',
                    'email' => 'employee@company2.com',
                    'name' => 'Employee Company 2',
                    'firstName' => 'Employee',
                    'lastName' => 'Company 2',
                    'avatar' => null,
                    'position' => 'Employee',
                    'companyId' => '8d80a3e9-515d-4974-927d-4b097d1eb9fe',
                    'companyName' => 'Jincor',
                    'companyLogo' => null,
                    'added' => true,
                    'matrixId' => '@8d80a3e9-515d-4974-927d-4b097d1eb9fe_employee_company2.com',

                ],
            ],
            'meta' => [
                'pagination' => [
                    'total' => 1,
                    'perPage' => 10,
                    'currentPage' => 1,
                    'lastPage' => 1,
                    'nextPageUrl' => null,
                    'prevPageUrl' => null,
                    'from' => 1,
                    'to' => 1,
                ],
            ],
        ]);
    }

    public function searchWithoutEmail(ApiTester $I)
    {
        $I->amAuthorizedAsJincorAdmin('123');
        $I->wantTo('Search for new contacts without email and receive validation error');
        $I->sendGET('employee/contacts/search');

        $message = trans('validation.required', [
            'attribute' => trans('email'),
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'email' => [
                $message
            ],
        ]);
    }

    public function searchInvalidEmail(ApiTester $I)
    {
        $I->amAuthorizedAsJincorAdmin('123');
        $I->wantTo('Search for new contacts with invalid email and receive validation error');
        $I->sendGET('employee/contacts/search', [
            'email' => 'invalid.email.com',
        ]);

        $message = trans('validation.email', [
            'attribute' => trans('email'),
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'email' => [
                $message
            ],
        ]);
    }
}
