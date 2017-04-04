<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/30/17
 * Time: 6:32 PM
 */
use Illuminate\Database\Seeder;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Core\Dictionary\Entities\Country;
use App\Domains\Employee\ValueObjects\EmployeeProfile;
use App\Domains\Employee\Services\EmployeeVerificationService;
use App\Domains\Employee\Services\EmployeeService;
use Doctrine\ODM\MongoDB\DocumentRepository;
use GeoJson\Geometry\Point;
use App\Domains\Company\Entities\Company;
use App\Core\ValueObjects\Address;
use App\Domains\Company\Entities\CompanyType;

class CompanySeeder extends Seeder
{
    /**
     * @var EmployeeVerificationService
     */
    private $evs;

    /**
     * @var EmployeeService
     */
    private $ers;


    public function run()
    {
        $country = $this->getCountryRepository()->findByAlpha2Code('RU');
        $point = new Point([
            55.7741912,
            37.6285343,
        ]);
        $address = new Address('Москва, ул. Алая, д. 15, оф. 89, 602030', $country, $point);
        /** @var CompanyType $companyType */
        $companyType = $this->getDm()->getRepository(CompanyType::class)->findOneBy([
            'code' => 'BT1',
        ]);
        $company = new Company(env('TEST_COMPANY_NAME'), $address, $companyType);
        $this->getDm()->persist($company);
        $this->getDm()->flush();
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    private function getDm() : DocumentManager
    {
        return $this->container->make(DocumentManager::class);
    }

    /**
     * @return DocumentRepository
     */
    private function getCountryRepository() : DocumentRepository
    {
        return $this->getDm()->getRepository(Country::class);
    }
}
