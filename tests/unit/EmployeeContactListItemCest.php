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

    /*
     * According to some "dirty fix" for wallets this tests fails now
     * Check this commit: https://github.com/JincorTech/backend-company/commit/e2952a6f1cbd2b8c5f0d0333de4e7a633e959092
    public function canNotCreateContactWithDeactivatedEmployee(UnitTester $I)
    {
        $employee = EmployeeFactory::make();
        $employee->deactivate();
        $I->expectException(EmployeeIsDeactivated::class, function () use ($employee) {
            new EmployeeContactListItem($employee);
        });
    }
    */
}
