<?php
use App\Domains\Employee\ValueObjects\EmployeeContactListItem;
use App\Domains\Employee\Exceptions\EmployeeIsDeactivated;
use App\Core\Interfaces\IdentityInterface;

class EmployeeContactListItemCest
{
    public function _before(UnitTester $I)
    {
        $identityMock = Mockery::mock(IdentityInterface::class);
        $identityMock->shouldReceive('register')->once()->andReturn(true);
        App::instance(IdentityInterface::class, $identityMock);
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
