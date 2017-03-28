<?php

use App\Core\Dictionary\Repositories\CountryRepository;

class CountryRepositoryCest
{
    /**
     * @var \App\Core\Dictionary\Repositories\CountryRepository
     */
    private $repository;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentRepository
     */
    private $currencyRepository;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    private $dm;

    public function _before(UnitTester $I)
    {
        $this->dm = $I->getDocumentManager();
        $this->repository = $this->dm->getRepository(\App\Core\Dictionary\Entities\Country::class);
        $this->currencyRepository = $this->dm->getRepository(\App\Core\Dictionary\Entities\Currency::class);
    }

    public function _after(UnitTester $I)
    {
    }

    public function isRepositoryValid(UnitTester $I)
    {
        $I->assertInstanceOf(CountryRepository::class, $this->repository);
    }

    /**
     * @param UnitTester $I
     */
    public function findByNumericCodeTest(UnitTester $I)
    {
        $correctCode = 643;
        $wrongCode = 10020;
        $notExistingCode = 1;
        $correctCountry = $this->repository->findByNumericCode($correctCode);
        $I->assertNotEmpty($correctCountry);
        $I->expectException(InvalidArgumentException::class, function () use ($wrongCode) {
            $this->repository->findByNumericCode($wrongCode);
        });
        $notExistingCountry = $this->repository->findByNumericCode($notExistingCode);
        $I->assertEmpty($notExistingCountry);
    }

    /**
     * @param UnitTester $I
     */
    public function findByAlpha2Test(UnitTester $I)
    {
        $correctCode = 'RU';
        $wrongCode = 'RUS';
        $notExistingCode = 'ZG';
        $correctCountry = $this->repository->findByAlpha2Code($correctCode);
        $I->assertNotEmpty($correctCountry);
        $I->expectException(InvalidArgumentException::class, function () use ($wrongCode) {
            $this->repository->findByAlpha2Code($wrongCode);
        });
        $notExistingCountry = $this->repository->findByAlpha2Code($notExistingCode);
        $I->assertEmpty($notExistingCountry);
    }

    /**
     * @param UnitTester $I
     */
    public function findByAlpha3Test(UnitTester $I)
    {
        $correctCode = 'RUS';
        $wrongCode = 'RU';
        $notExistingCode = 'ZGA';
        $correctCountry = $this->repository->findByAlpha3Code($correctCode);
        $I->assertNotEmpty($correctCountry);
        $I->expectException(InvalidArgumentException::class, function () use ($wrongCode) {
            $this->repository->findByAlpha3Code($wrongCode);
        });
        $notExistingCountry = $this->repository->findByAlpha3Code($notExistingCode);
        $I->assertEmpty($notExistingCountry);
    }

    /**
     * @param UnitTester $I
     */
    public function findByISO2Test(UnitTester $I)
    {
        $correctCode = 'ISO 3166-2:RU';
        $wrongCode = 'ISO 3166-2:RUS';
        $notExistingCode = 'ISO 3166-2:ZG';
        $correctCountry = $this->repository->findByISO2Code($correctCode);
        $I->assertNotEmpty($correctCountry);
        $I->expectException(InvalidArgumentException::class, function () use ($wrongCode) {
            $this->repository->findByISO2Code($wrongCode);
        });
        $notExistingCountry = $this->repository->findByISO2Code($notExistingCode);
        $I->assertEmpty($notExistingCountry);
    }

    /**
     * @param UnitTester $I
     */
    public function findByNameTest(UnitTester $I)
    {
        $existingName = 'Russia';
        $notExistingName = 'Krakozhia';
        $existingNameRu = 'Россия';
        $notExistingNameRu = 'Кракожия';
        $I->assertNotEmpty($this->repository->findByName($existingName));
        $I->assertNotEmpty($this->repository->findByName($existingNameRu, 'ru'));
        $I->assertEmpty($this->repository->findByName($notExistingName));
        $I->assertEmpty($this->repository->findByName($notExistingNameRu, 'ru'));
    }

    /**
     * @param UnitTester $I
     */
    public function findByPhoneCodeTest(UnitTester $I)
    {
        $existingCode = '+7';
        $notExistingCode = '+77374';
        $I->assertNotEmpty($this->repository->findByPhoneCode($existingCode));
        $I->assertEmpty($this->repository->findByPhoneCode($notExistingCode));
    }

    /**
     * @param UnitTester $I
     */
    public function findByCurrencyTest(UnitTester $I)
    {
        /** @var \App\Core\Dictionary\Entities\Currency $correctCurrency */
        $correctCurrency = $this->currencyRepository->findOneBy([
            'names.en' => 'Russian ruble',
        ]);
        $I->assertNotEmpty($this->repository->findByCurrency($correctCurrency));
    }

    /**
     * @param UnitTester $I
     */
    public function findByCurrencyCodeTest(UnitTester $I)
    {
        $I->assertNotEmpty($this->repository->findByCurrencyCode('RUB'));
        $I->expectException(InvalidArgumentException::class, function () {
            $this->repository->findByCurrencyCode('RUBL');
        });
        $I->expectException(InvalidArgumentException::class, function () {
            $this->repository->findByCurrencyCode('RU');
        });
        $I->assertEmpty($this->repository->findByCurrencyCode('ZGD'));
    }
}
