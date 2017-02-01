<?php

/**
 * Class EmployeeCest.
 *
 * Test API Endpoints related to Company\EmployeeController
 */
class EmployeeCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Company\Http\Controllers\EmployeeController::resetPassword
     *
     * Should return 200 status if successfully
     * Check for errors if invalid password were provided
     * Check for errors if wrong user
     */
    public function resetPasswordTest(ApiTester $I)
    {
        $I->sendPUT('employee/resetPassword');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Company\Http\Controllers\EmployeeController::verifyEmail
     *
     * Should return 200 status code if OK
     *
     * Check for errors if email is invalid
     * Check for errors if PIN is invalid
     */
    public function verifyEmailTest(ApiTester $I)
    {
        $I->sendGET('employee/verifyEmail');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Company\Http\Controllers\EmployeeController::verifyPhone
     *
     * Should return 200 status code if OK
     *
     * Check for errors if phone is invalid
     * Check for errors if PIN is invalid
     */
    public function verifyPhoneTest(ApiTester $I)
    {
        $I->sendGET('employee/verifyPhone');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Company\Http\Controllers\EmployeeController::info
     *
     * Should return Employee instance
     * Check for errors if invalid Employee ID were provided
     */
    public function getInfoTest(ApiTester $I)
    {
        $I->sendGET('employee/1');
        $I->seeResponseCodeIs(200);
    }
}
