<?php

use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Company\Services\CompanyService;
use App\Domains\Employee\Interfaces\EmployeeVerificationServiceInterface;

class CompanyServiceCest
{

    /**
     * @var CompanyService
     */
    private $companyService;

    /**
     * @var DocumentManager|mixed
     */
    private $dm;

    /**
     * CompanyServiceCest constructor.
     */
    public function __construct()
    {
        $this->dm = App::make(DocumentManager::class);
        $this->companyService = new CompanyService(App::make(EmployeeVerificationServiceInterface::class));
    }

    public function getCompany(UnitTester $I)
    {
        $company = CompanyFactory::make();
        $this->dm->persist($company);
        $this->dm->flush();
        $find = $this->companyService->getCompany($company->getId());
        $I->assertEquals($company, $find);
    }

    public function getCompanyTypes(UnitTester $I)
    {
        $I->assertNotEmpty($this->companyService->getCompanyTypes());
    }

    public function getEconomicalActivityTypes(UnitTester $I)
    {
        $I->assertNotEmpty($this->companyService->getEconomicalActivityTypes());
    }

    public function getEARoot(UnitTester $I)
    {
        $I->assertNotEmpty($this->companyService->getEARoot()->toArray());
    }
}
