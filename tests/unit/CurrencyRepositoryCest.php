<?php

use App\Core\Dictionary\Entities\Currency;

class CurrencyRepositoryCest
{
    private $dm;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentRepository
     */
    private $repository;

    public function _before(UnitTester $I)
    {
        $this->dm = App::make(\Doctrine\ODM\MongoDB\DocumentManager::class);
        $this->repository = $this->dm->getRepository(\App\Core\Dictionary\Entities\Currency::class);
    }

    public function _after(UnitTester $I)
    {
    }

    /**
     * @param UnitTester $I
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    public function findByUuidTest(UnitTester $I)
    {
        $all = $this->repository->findAll();
        /** @var \App\Core\Dictionary\Entities\Currency $first */
        $first = $all[0];
        /** @var \App\Core\Dictionary\Entities\Currency $currency */
        $currency = $this->repository->find($first->getId());
        $I->assertInstanceOf(Currency::class, $currency);
        $I->assertEquals($first->getId(), $currency->getId());
        $wrongIdCurrency = $this->repository->find('1e8afc9f-e4f2-4dd6-884d-82e411afab8');
        $I->assertNull($wrongIdCurrency);
    }

    /**
     * @param UnitTester $I
     */
    public function findByCodeTest(UnitTester $I)
    {
        $dollar = $this->repository->findBy(['ISOCodes.alpha3Code' => 'USD']);
        $I->assertNotEmpty($dollar);
        $I->assertArrayHasKey(0, $dollar);
        $I->assertInstanceOf(Currency::class, $dollar[0]);
        $unexistingCurrency = $this->repository->findBy(['ISOCodes.alpha3Code' => 'ZGD']);
        $I->assertEmpty($unexistingCurrency);
    }

    /**
     * @param UnitTester $I
     */
    public function findByNumericCodeTest(UnitTester $I)
    {
        $dollar = $this->repository->findBy([
            'ISOCodes.numericCode' => 840,
        ]);
        $I->assertNotEmpty($dollar);
        $I->assertArrayHasKey(0, $dollar);
        $I->assertInstanceOf(Currency::class, $dollar[0]);
        $notExistingCurrency = $this->repository->findBy([
            'ISOCodes.numericCode' => 1001,
        ]);
        $I->assertEmpty($notExistingCurrency);
    }

    /**
     * @param UnitTester $I
     */
    public function findByNameTest(UnitTester $I)
    {
        $dollar = $this->repository->findBy([
            'names.en' => $this->firstCurrency()->getName('en'),
        ]);
        $this->currencyExists($I, $dollar);
        $russianName = $this->repository->findBy([
            'names.ru' => $this->firstCurrency()->getName('ru'),
        ]);
        $this->currencyExists($I, $russianName);
        $notExistingCurrency = $this->repository->findBy([
            'names.en' => 'US dolla',
        ]);
        $I->assertEmpty($notExistingCurrency);
    }

    public function firstCurrency() : Currency
    {
        return $this->repository->findAll()[0];
    }

    /**
     * @param UnitTester $I
     * @param array $currencies
     */
    private function currencyExists(UnitTester $I, array $currencies)
    {
        $I->assertNotEmpty($currencies);
        $I->assertArrayHasKey(0, $currencies);
        $I->assertInstanceOf(Currency::class, $currencies[0]);
    }
}
