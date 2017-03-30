<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/19/17
 * Time: 8:36 PM
 */

namespace App\Domains\Company\Services;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Domains\Employee\Services\EmployeeVerificationService;
use App\Domains\Company\Entities\EconomicalActivityType;
use App\Domains\Company\Entities\CompanyType;
use App\Domains\Employee\Entities\Employee;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Company\Entities\Company;
use App\Core\Services\AddressService;
use App;

class CompanyService
{
    /**
     * @var DocumentManager|mixed
     */
    private $dm;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentRepository
     */
    private $repository;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentRepository
     */
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
     * Register new company
     *
     * @param string $country
     * @param string $legalName
     * @param string $companyType
     * @return App\Domains\Employee\Entities\EmployeeVerification
     */
    public function register(
        string $country,
        string $legalName,
        string $companyType
    ) {
        try {
            $address = $this->address()->build($country);
        } catch (\InvalidArgumentException $e) {
            throw new UnprocessableEntityHttpException(trans('registration.countryNotFound', [
                'country' => $country,
            ]));
        }
        $ct = $this->dm->getRepository(CompanyType::class)->find($companyType);
        if (!$ct) {
            throw new UnprocessableEntityHttpException(trans('registration.typeNotFound', [
                'ct' => $companyType,
            ]));
        }
        $company = new Company($legalName, $address, $ct);
        $this->dm->persist($company);

        return $this->verification()->beginVerificationProcess($company);
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


    /**
     * @return array|mixed
     */
    public function getEARoot()
    {
        return $this->eActivityRepository->getRootNodes();
    }

    /**
     * TODO: replace with DI
     * @return AddressService
     */
    private function address()
    {
        return new AddressService();
    }

    /**
     * @TODO: replace with DI
     * @return EmployeeVerificationService
     */
    private function verification()
    {
        return new EmployeeVerificationService();
    }

}
