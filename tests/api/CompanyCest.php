<?php

/**
 * Class CompanyCest.
 *
 * Test cases for testing all the endpoints related Company\CompanyController
 */
class CompanyCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Company\Http\Controllers\CompanyController::info
     *
     * Should return Company instance
     * Check for errors if invalid Company ID provided
     */
    public function getCompanyTest(ApiTester $I)
    {
        $I->sendGET('company/1');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Company\Http\Controllers\CompanyController::listDepartments
     *
     * Should return the list of Department instances
     * Check for errors if invalid Company ID provided
     * Check for validity of the list(each department should be presented)
     * No content if the list is empty
     */
    public function getDepartmentsListTest(ApiTester $I)
    {
        $I->sendGET('company/1/departments');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Company\Http\Controllers\CompanyController::update
     * @covers \App\Applications\Company\Http\Controllers\CompanyController::store
     *
     * Should return updated Company instance
     * Check for errors if invalid Company instance provided
     * Check if all properties were updated
     */
    public function companyUpdateTest(ApiTester $I)
    {
        $I->sendPUT('company/1');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Company\Http\Controllers\CompanyController::setActivityTypes
     *
     * Should return updated Company instance
     * Check for errors if invalid Activities were provided
     * Check for errors if invalid Company ID were provided
     */
    public function setCompanyActivitiesTest(ApiTester $I)
    {
        $I->sendPUT('company/1/activities');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Company\Http\Controllers\CompanyController::setGoodsTypes
     *
     * Should return updated Company instance
     * Check for errors if invalid Goods were provided
     * Check for errors if invalid Company ID were provided
     */
    public function setCompanyGoodsTest(ApiTester $I)
    {
        $I->sendPUT('company/1/goods');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }

    /**
     * @param ApiTester $I
     * @covers \App\Applications\Company\Http\Controllers\CompanyController::register
     * @covers \App\Applications\Company\Http\Controllers\CompanyController::store
     */
    public function registerCompanyTest(ApiTester $I)
    {
        $I->sendPOST('company');
        $I->seeResponseCodeIs(200);
        //TODO: implement test body
    }
}
