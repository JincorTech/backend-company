<?php


class EmployeeLoginCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function loginByEmailAndPassword(ApiTester $I)
    {
        $mock = Mockery::mock(\App\Core\Interfaces\IdentityInterface::class);
        $mock->shouldReceive('login')->andReturn('randomtoken');
        $I->haveInstance(\App\Core\Interfaces\IdentityInterface::class, $mock);

        $I->wantTo('Login with correct email and password and receive my profile data as response');
        $I->sendPOST('employee/login', [
            'email' => 'test2@test.com',
            'password' => 'Cm3jpmrt7c',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson($I->dataOfJohnDoe());
    }

    public function loginByEmailPasswordAndCompanyId(ApiTester $I)
    {
        $mock = Mockery::mock(\App\Core\Interfaces\IdentityInterface::class);
        $mock->shouldReceive('login')->andReturn('randomtoken');
        $I->haveInstance(\App\Core\Interfaces\IdentityInterface::class, $mock);

        $I->wantTo('Login with correct email, password and company id and receive my profile data as response');
        $I->sendPOST('employee/login', [
            'companyId' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
            'email' => 'test2@test.com',
            'password' => 'Cm3jpmrt7c',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson($I->dataOfJohnDoe());
    }

    public function loginByEmailAndPasswordInvalidPassword(ApiTester $I)
    {
        $mock = Mockery::mock(\App\Core\Interfaces\IdentityInterface::class);
        $mock->shouldReceive('login')->andReturn(false);
        $I->haveInstance(\App\Core\Interfaces\IdentityInterface::class, $mock);

        $I->wantTo('Login with correct email and invalid password and receive empty data');
        $I->sendPOST('employee/login', [
            'email' => 'test2@test.com',
            'password' => 'Cm3jpmrt7c1',
        ]);

        //TODO: why is that?
        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseEquals('');
    }
}
