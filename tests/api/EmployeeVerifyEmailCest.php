<?php


class EmployeeVerifyEmailCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function sendVerification(ApiTester $I)
    {
        $I->wantTo('Request verification code and receive it to my email address');

        $I->sendGET('employee/verifyEmail', [
            'email' => 'notverified@tester.com',
            'verificationId' => '6b90fa0c-7912-452c-bddf-e2c718440251',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'data' => [
                'id' => '6b90fa0c-7912-452c-bddf-e2c718440251',
                'companyId' => '2862897b-3e88-4230-a9b7-570917704f56',
                'email' => [
                    'value' => 'notverified@tester.com',
                    'isVerified' => false
                ],
                'phone' => [
                    'value' => null,
                    'isVerified' => false
                ],
            ],
        ]);
    }

    public function sendVerificationIncorrectId(ApiTester $I)
    {
        $I->wantTo('Request verification code with incorrect veficication ID and receive 500 error code');

        $I->sendGET('employee/verifyEmail', [
            'email' => 'notverified@tester.com',
            'verificationId' => '6b90fa0c-7912-452c-bddf-e2c718440252',
        ]);

        $I->canSeeResponseCodeIs(500);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.employee.verification.not_found', [
                'verification' => '6b90fa0c-7912-452c-bddf-e2c718440252',
            ]),
            'status_code' => 500,
        ]);
    }

    public function verifyEmailCorrectCode(ApiTester $I)
    {
        $I->wantTo('Verify my email with correct code in order to continue registration process');

        $I->sendPOST('employee/verifyEmail', [
            'verificationCode' => '318379',
            'verificationId' => '6b90fa0c-7912-452c-bddf-e2c718440251',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseIsJson();
        $I->canSeeResponseContainsJson([
            'data' => [
                'id' => '6b90fa0c-7912-452c-bddf-e2c718440251',
                'companyId' => '2862897b-3e88-4230-a9b7-570917704f56',
                'email' => [
                    'value' => 'notverified@tester.com',
                    'isVerified' => true,
                ],
                'phone' => [
                    'value' => null,
                    'isVerified' => false
                ],
            ],
        ]);
    }

    public function verifyEmailIncorrectCode(ApiTester $I)
    {
        $I->wantTo('Verify my email with incorrect verification code and receive 401 response code');

        $I->sendPOST('employee/verifyEmail', [
            'verificationCode' => '318300',
            'verificationId' => '6b90fa0c-7912-452c-bddf-e2c718440251',
        ]);

        $I->canSeeResponseIsJson();
        $I->canSeeResponseCodeIs(401);
        $I->canSeeResponseContainsJson([
            'success' => false,
            'message' => trans('exceptions.verification.code.incorrect'),
        ]);
    }
}
