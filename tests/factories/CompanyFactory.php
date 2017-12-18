<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 12:09 AM
 */

use App\Domains\Company\Entities\CompanyType;
use App\Domains\Company\Entities\Company;
use App\Core\Dictionary\Entities\Country;
use App\Core\ValueObjects\Address;
use Doctrine\Common\Collections\ArrayCollection;
use Faker\Factory;
use Doctrine\ODM\MongoDB\DocumentManager;

class CompanyFactory implements FactoryInterface
{
    /**
     * Make a new random company
     * @return Company
     */
    public static function make()
    {
        $ru = Factory::create('ru_RU');

        $companyType = CompanyTypeFactory::make();

        return new Company($ru->company, AddressFactory::make(), $companyType);
    }


    /**
     * @return Company
     */
    public static function makeMockWith1Employee()
    {
        $company = Mockery::mock(Company::class);

        //just random collection to have count > 0
        $collection = new ArrayCollection([
            '123',
        ]);
        $companyProfile = CompanyFactory::make()->getProfile();
        $department = Mockery::mock(\App\Domains\Company\Entities\Department::class);
        $department->shouldReceive('getCompany')->andReturn($company);
        $department->shouldReceive('addEmployee')->andReturn(null);
        $department->shouldReceive('getId')->andReturn('id');

        $company->shouldReceive('getEmployees')->andReturn($collection);
        $company->shouldReceive('getId')->andReturn('id');
        $company->shouldReceive('getRootDepartment')->andReturn($department);
        $company->shouldReceive('getProfile')->andReturn($companyProfile);

        return $company;
    }

    /**
     * @return Company
     */
    public static function makeTestCompanyFromDb() : Company
    {
        $id = base64_decode('OWZjYWQ3YzUtZjg0ZS00ZDQzLWIzNWMtMDVlNjlkMGUwMzYy');
        $company = App::make(DocumentManager::class)->getRepository(Company::class)->find($id);
        return $company;
    }
}
