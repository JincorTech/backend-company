<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/19/17
 * Time: 8:36 PM
 */

namespace App\Domains\Company\Services;

use App\Domains\Company\Entities\Company;
use App\Domains\Company\Entities\CompanyType;
use App\Domains\Company\Entities\EconomicalActivityType;
use Doctrine\ODM\MongoDB\DocumentManager;
use App;

class CompanyService
{
    private $dm;

    private $repository;

    private $typesRepository;

    /**
     * @var App\Domains\Company\Repositories\EconomicalActivityRepository
     */
    private $eActivityRepository;

    public function __construct()
    {
        $this->dm = App::make(DocumentManager::class);
        $this->repository = $this->dm->getRepository(Company::class);
        $this->typesRepository = $this->dm->getRepository(CompanyType::class);
        $this->eActivityRepository = $this->dm->getRepository(EconomicalActivityType::class);
    }

    /**
     * @param string $id
     * @return null|Company
     */
    public function getCompany(string $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return array
     */
    public function getCompanyTypes()
    {
        return $this->typesRepository->findAll();
    }

    /**
     * @return array
     */
    public function getEconomicalActivityTypes()
    {
        return $this->eActivityRepository->findAll();
    }

    public function getEATree($type)
    {
        return $type->getChildren()->getValues();
    }

    public function getEARoot()
    {
        return $this->eActivityRepository->getRootNodes();
    }
}
