<?php
use App\Domains\Employee\ValueObjects\EmployeeContactListItem;
use App\Domains\Employee\Exceptions\EmployeeIsDeactivated;

class EmployeeContactListItemCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    public function canNotCreateContactWithDeactivatedEmployee(UnitTester $I)
    {
        $employee = EmployeeFactory::make();
        $employee->deactivate();
        $I->expectException(EmployeeIsDeactivated::class, function () use ($employee) {
            new EmployeeContactListItem($employee);
        });
    }
}
