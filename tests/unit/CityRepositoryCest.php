<?php

use App\Core\Dictionary\Repositories\CountryRepository;
use App\Core\Dictionary\Repositories\CityRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Core\Dictionary\Entities\Country;
use App\Core\Dictionary\Entities\City;

class CityRepositoryCest
{
    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * @var CountryRepository
     */
    private $countryRepository;


    public function _before(UnitTester $I)
    {
        $this->dm = $I->getDocumentManager();
        $this->countryRepository = $this->dm->getRepository(Country::class);
        $this->cityRepository = $this->dm->getRepository(City::class);
    }


    public function isCorrectRepositoryClass(UnitTester $I)
    {
        $I->assertInstanceOf(CityRepository::class, $this->cityRepository);
    }

    public function testSaveAndFind(UnitTester $I)
    {
        $city = CityFactory::make();
        $this->dm->persist($city);
        $this->dm->persist($city->getCountry());
        $this->dm->flush();
        $found = $this->cityRepository->find($city->getId());
        $I->assertEquals($city->getId(), $found->getId());
    }

    public function testFindByCountry(UnitTester $I)
    {
        $city = CityFactory::make();
        $this->dm->persist($city->getCountry());
        $this->dm->persist($city);
        $this->dm->flush();

        $found = $this->cityRepository->findInCountry($city->getCountry());
        $I->assertNotEmpty($found);
        $I->assertEquals($city->getCountry()->getId(), $found->first()->getCountry()->getId());
    }
}
