<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/20/16
 * Time: 2:56 PM
 */

namespace App\Core\Dictionary\Repositories;

use App\Core\Dictionary\Entities\Country;
use App\Core\Dictionary\Entities\Currency;
use Doctrine\ODM\MongoDB\Cursor;
use Doctrine\ODM\MongoDB\DocumentRepository;

class CountryRepository extends DocumentRepository
{
    /**
     * @param int $code
     * @return \App\Core\Dictionary\Entities\Country|null
     */
    public function findByNumericCode(int $code)
    {
        if ($code < 1 || $code > 999) {
            throw new \InvalidArgumentException('Country code must be between 1 and 999');
        }

        return $this->findOneBy([
            'ISOCodes.numericCode' => $code,
        ]);
    }

    /**
     * @param string $alpha2Code
     * @return \App\Core\Dictionary\Entities\Country|null
     */
    public function findByAlpha2Code(string $alpha2Code)
    {
        if (strlen($alpha2Code) !== 2) {
            throw new \InvalidArgumentException('Alpha 2 code must be 2 chars length');
        }

        return $this->findOneBy([
            'ISOCodes.alpha2Code' => $alpha2Code,
        ]);
    }

    /**
     * @param string $alpha3Code
     * @return Country|null
     */
    public function findByAlpha3Code(string $alpha3Code)
    {
        if (strlen($alpha3Code) !== 3) {
            throw new \InvalidArgumentException('Alpha 3 code must be 3 chars length');
        }

        return $this->findOneBy([
            'ISOCodes.alpha3Code' => $alpha3Code,
        ]);
    }

    /**
     * @param string $ISO2Code
     * @return Country|null
     */
    public function findByISO2Code(string $ISO2Code)
    {
        if (strlen($ISO2Code) !== 13) {
            throw new \InvalidArgumentException('ISO2Code must be 13 characters length');
        }

        return $this->findOneBy([
            'ISOCodes.ISO2Code' => $ISO2Code,
        ]);
    }

    /**
     * @param string $name
     * @param string $locale
     * @return Country|null
     */
    public function findByName(string $name, string $locale = 'en')
    {
        $nameKey = 'names.'.$locale;

        return $this->findOneBy([
            $nameKey => $name,
        ]);
    }

    /**
     * @param string $phoneCode
     * @return array[Country]
     */
    public function findByPhoneCode(string $phoneCode)
    {
        return $this->findBy([
            'phoneCode' => $phoneCode,
        ]);
    }

    /**
     * @param Currency $currency
     * @return array[Country]
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function findByCurrency(Currency $currency)
    {
        /** @var Cursor $cursor */
        $cursor = $this->createQueryBuilder()->field('currency')
            ->references($currency)->getQuery()->execute();

        return $cursor->toArray();
    }

    /**
     * @param string $currencyCode
     * @return array[Country]
     */
    public function findByCurrencyCode(string $currencyCode)
    {
        if (strlen($currencyCode) !== 3) {
            throw new \InvalidArgumentException('Currency code must be 3 characters length');
        }
        $currencyRepository = $this->getDocumentManager()->getRepository(Currency::class);
        /** @var \App\Core\Dictionary\Entities\Currency $currency */
        $currency = $currencyRepository->findOneBy([
            'ISOCodes.alpha3Code' => $currencyCode,
        ]);
        if ($currency) {
            return $this->findByCurrency($currency);
        }

        return [];
    }
}
