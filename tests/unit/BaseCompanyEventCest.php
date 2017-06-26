<?php
use App\Domains\Company\Events\BaseCompanyEvent;

class BaseCompanyEventCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function testGetCompany(UnitTester $I)
    {
        $company = CompanyFactory::make();
        $event = new BaseCompanyEvent($company);

        $I->assertEquals($company, $event->getCompany());
    }
}
