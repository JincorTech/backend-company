<?php

class EmployeeAdminCest
{
    public function _before(ApiTester $I)
    {
        $I->haveWalletsMock();
    }

    public function _after(ApiTester $I)
    {
    }

    public function success(ApiTester $I)
    {
        $I->wantTo('Give an employee admin rights in my company and receive success response');
        $I->amAuthorizedAsJincorAdmin('123');

        $I->sendPUT('employee/admin', [
            'id' => '9617881b-3ae9-4a7f-82b9-e2f46568f0ca',
            'value' => true,
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'role' => 'company-admin',
        ]);
    }

    public function adminTryToRevokeAnotherAdmin(ApiTester $I)
    {
        $I->wantTo('Revoke admin rights of another company and receive access denied error');
        $I->amAuthorizedAsJincorAdmin('123');

        $I->sendPUT('employee/admin', [
            'id' => '63e88d9d-a79e-4705-9c8f-8712b71b53f8',
            'value' => false,
        ]);
        $I->canSeeResponseCodeIs(403);
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.access_denied'),
            'status_code' => 403,
        ]);
    }

    public function adminAssignAdminToEmployeeOfAnotherCompany(ApiTester $I)
    {
        $I->wantTo('Assign admin rights to employee of another company and receive access denied error');
        $I->amAuthorizedAsJincorAdmin('123');

        $I->sendPUT('employee/admin', [
            'id' => '3e696895-ab1b-44ec-8646-86067877e38o',
            'value' => false,
        ]);

        $I->canSeeResponseCodeIs(403);
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.access_denied'),
            'status_code' => 403,
        ]);
    }

    public function employeeTryToRevokeAdmin(ApiTester $I)
    {
        $I->wantTo('Revoke admin rights as employee and receive access denied error');
        $I->amAuthorizedAsJincorEmployee('123');

        $I->sendPUT('employee/admin', [
            'id' => '3e696895-ab1b-44ec-8646-86067877e38c',
            'value' => false,
        ]);

        $I->canSeeResponseCodeIs(403);
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.access_denied'),
            'status_code' => 403,
        ]);
    }

    public function employeeTryToAssignAdminMyself(ApiTester $I)
    {
        $I->wantTo('Assign admin rights to myself as employee and receive access denied error');
        $I->amAuthorizedAsJincorEmployee('123');

        $I->sendPUT('employee/admin', [
            'id' => '9617881b-3ae9-4a7f-82b9-e2f46568f0ca',
            'value' => false,
        ]);

        $I->canSeeResponseCodeIs(403);
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.access_denied'),
            'status_code' => 403,
        ]);
    }

    public function employeeTryToRevokeAdminOfAnotherCompany(ApiTester $I)
    {
        $I->wantTo('Revoke admin rights of another company as employee and receive access denied error');
        $I->amAuthorizedAsJincorEmployee('123');

        $I->sendPUT('employee/admin', [
            'id' => '63e88d9d-a79e-4705-9c8f-8712b71b53f8',
            'value' => false,
        ]);

        $I->canSeeResponseCodeIs(403);
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.access_denied'),
            'status_code' => 403,
        ]);
    }

}
