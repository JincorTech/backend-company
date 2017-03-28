<?php

use App\Domains\Company\Entities\Company;
use App\Domains\Company\ValueObjects\CompanyProfile;
use App\Domains\Company\Entities\Department;
use Doctrine\Common\Collections\ArrayCollection;

class CompanyCest
{

    public function createCompany(UnitTester $I)
    {
        $company = CompanyFactory::make();
        $I->assertInstanceOf(Company::class, $company);
        $I->assertInstanceOf(CompanyProfile::class, $company->getProfile());
        $I->assertInstanceOf(Department::class, $company->getRootDepartment());
        $I->assertInstanceOf(ArrayCollection::class, $company->getEmployees());
    }


}
