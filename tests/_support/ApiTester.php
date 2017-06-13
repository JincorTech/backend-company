<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param array $errors
     */
    public function canSeeResponseContainsValidationErrors(array $errors)
    {
        $this->canSeeResponseCodeIs(422);
        $this->canSeeResponseIsJson();
        $this->canSeeResponseContainsJson([
            'message' => '422 Unprocessable Entity',
            'errors' => $errors,
        ]);
    }

    /**
     * @param string $message
     */
    public function canSeeResponseContainsValidationMessage(string $message)
    {
        $this->canSeeResponseCodeIs(422);
        $this->canSeeResponseIsJson();
        $this->canSeeResponseContainsJson([
            'message' => $message,
        ]);
    }

    public function amAuthorizedAsTestCompanyAdmin(string $token)
    {
        $mock = Mockery::mock(\App\Core\Interfaces\IdentityInterface::class);

        $mock->shouldReceive('validateToken')
            ->with($token)
            ->andReturn([
                'id' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362',
                'login' => '9fcad7c5-f84e-4d43-b35c-05e69d0e0362:test2@test.com',
                'scope' => 'company-admin',
                'deviceId' => '12345',
                'jti' => 'b6c86c95-bbe6-4249-9be8-2045216ac015123451496755690064',
                'iat' => 1496755690064,
                'exp' => 1496756294864,
            ]);

        $mock->shouldReceive('register')->andReturn(true);

        $mock->shouldReceive('login')->andReturn('randomtoken');

        $this->haveInstance(\App\Core\Interfaces\IdentityInterface::class, $mock);
        $this->amBearerAuthenticated($token);
    }

    public function amAuthorizedAsJincorAdmin(string $token)
    {
        $mock = Mockery::mock(\App\Core\Interfaces\IdentityInterface::class);

        $mock->shouldReceive('validateToken')
            ->with($token)
            ->andReturn([
                'id' => '8d80a3e9-515d-4974-927d-4b097d1eb9fe',
                'login' => '8d80a3e9-515d-4974-927d-4b097d1eb9fe:admin@company2.com',
                'scope' => 'company-admin',
                'deviceId' => '12345',
                'jti' => 'b6c86c95-bbe6-4249-9be8-2045216ac015123451496755690064',
                'iat' => 1496755690064,
                'exp' => 1496756294864,
            ]);

        $mock->shouldReceive('register')->andReturn(true);

        $mock->shouldReceive('login')->andReturn('randomtoken');

        $this->haveInstance(\App\Core\Interfaces\IdentityInterface::class, $mock);
        $this->amBearerAuthenticated($token);
    }

    public function amAuthorizedAsJincorEmployee(string $token)
    {
        $mock = Mockery::mock(\App\Core\Interfaces\IdentityInterface::class);

        $mock->shouldReceive('validateToken')
            ->with($token)
            ->andReturn([
                'id' => '9617881b-3ae9-4a7f-82b9-e2f46568f0ca',
                'login' => '8d80a3e9-515d-4974-927d-4b097d1eb9fe:employee@company2.com',
                'scope' => 'employee',
                'deviceId' => '12345',
                'jti' => 'b6c86c95-bbe6-4249-9be8-2045216ac015123451496755690064',
                'iat' => 1496755690064,
                'exp' => 1496756294864,
            ]);

        $mock->shouldReceive('register')->andReturn(true);

        $mock->shouldReceive('login')->andReturn('randomtoken');

        $this->haveInstance(\App\Core\Interfaces\IdentityInterface::class, $mock);
        $this->amBearerAuthenticated($token);
    }

    public function haveStorageMockForAvatarUpload(string $url)
    {
        $mock = Mockery::mock(\Illuminate\Filesystem\FilesystemAdapter::class);

        $mock->shouldReceive('put')->andReturnNull();
        $mock->shouldReceive('url')->andReturn($url);

        Storage::shouldReceive('disk')->andReturn($mock);
    }
}
