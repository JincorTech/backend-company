<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 12/6/16
 * Time: 11:34 PM
 */

namespace App\Applications\Company\Services\Company;

use App\Core\Dictionary\Entities\Country;
use App\Core\Services\INNInfo\DadataService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Domains\Company\ValueObjects\InfoByTaxNumber;
use App;

class TaxInfoService
{
    /**
     * @var App\Core\Services\INNInfo\INNInfoInterface
     */
    public $vendorService;

    /**
     * @var Country
     */
    public $country;

    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var  DocumentRepository
     */
    private $companyTypeRepo;

    /**
     * TaxInfoService constructor.
     * @param Country $country
     */
    public function __construct(Country $country)
    {
        $this->country = $country;
        $this->dm = App::make(DocumentManager::class);
        $this->companyTypeRepo = $this->dm->getRepository(App\Domains\Company\Entities\CompanyType::class);
        if ($country->getAlpha2Code() === 'RU') {
            $this->vendorService = new DadataService();
        } else {
            throw new HttpException(500, 'Tax number info service not found for provided country');
        }
    }

    /**
     * @param string $INN
     * @return InfoByTaxNumber|null
     * @throws \HttpException
     */
    public function getInfo(string $INN)
    {
        $rawData = $this->vendorService->getInfoByINN($INN);
        if ($rawData !== null) {
            return $this->makeValueObject($rawData);
        }

        return;
    }

    /**
     * @param array $data
     * @return InfoByTaxNumber
     */
    public function makeValueObject(array $data)
    {
        $ct = $this->getCompanyTypeByName($data['companyType']);

        return new InfoByTaxNumber(
            $data['legalName'],
            $data['taxNumber'],
            $data['formattedAddress'],
            $ct
        );
    }

    /**
     * @param string $typeName
     * @return null|string
     */
    private function getCompanyTypeByName(string $typeName)
    {
        if ($this->country->getAlpha2Code() === 'RU') {
            /** @var App\Domains\Company\Entities\CompanyType $companyType */
            $companyType = $this->companyTypeRepo->findOneBy([
                'names.ru' => $typeName,
            ]);
            if ($companyType) {
                return $companyType->getId();
            }

            return;
        }

        return;
    }
}
