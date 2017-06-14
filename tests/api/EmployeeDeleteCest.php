<?php


class EmployeeDeleteCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function success(ApiTester $I)
    {
        $I->wantTo('Delete an employee of my company and receive success response');
        $I->amAuthorizedAsJincorAdmin('123');

        $I->sendDELETE('employee/' . '9617881b-3ae9-4a7f-82b9-e2f46568f0ca');

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'status' => 'deleted',
        ]);
    }

    public function adminTryToDeleteAdminAnotherCompany(ApiTester $I)
    {
        $I->wantTo('Delete admin of another company and receive access denied error');
        $I->amAuthorizedAsJincorAdmin('123');

        $I->sendDELETE('employee/' . '63e88d9d-a79e-4705-9c8f-8712b71b53f8');

        $I->canSeeResponseCodeIs(403);
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.access_denied'),
            'status_code' => 403,
        ]);
    }

    public function adminDeleteEmployeeOfAnotherCompany(ApiTester $I)
    {
        $I->wantTo('Delete employee of another company and receive access denied error');
        $I->amAuthorizedAsJincorAdmin('123');

        $I->sendDELETE('employee/' . '3e696895-ab1b-44ec-8646-86067877e38o');

        $I->canSeeResponseCodeIs(403);
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.access_denied'),
            'status_code' => 403,
        ]);
    }

    public function employeeTryToDeleteAdmin(ApiTester $I)
    {
        $I->wantTo('Delete admin as employee and receive access denied error');
        $I->amAuthorizedAsJincorEmployee('123');

        $I->sendDELETE('employee/' . '3e696895-ab1b-44ec-8646-86067877e38c');

        $I->canSeeResponseCodeIs(403);
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.access_denied'),
            'status_code' => 403,
        ]);
    }

    public function employeeTryToDeleteMyself(ApiTester $I)
    {
        $I->wantTo('Delete myself as employee and receive access denied error');
        $I->amAuthorizedAsJincorEmployee('123');

        $I->sendDELETE('employee/' . '9617881b-3ae9-4a7f-82b9-e2f46568f0ca');

        $I->canSeeResponseCodeIs(403);
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.access_denied'),
            'status_code' => 403,
        ]);
    }

    public function employeeDeleteAdminOfAnotherCompany(ApiTester $I)
    {
        $I->wantTo('Delete admin of another company as employee and receive access denied error');
        $I->amAuthorizedAsJincorEmployee('123');

        $I->sendDELETE('employee/' . '63e88d9d-a79e-4705-9c8f-8712b71b53f8');

        $I->canSeeResponseCodeIs(403);
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.access_denied'),
            'status_code' => 403,
        ]);
    }
}
