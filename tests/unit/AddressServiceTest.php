<?php


class AddressServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected $dm;

    /**
     * @var \App\Core\Dictionary\Repositories\CountryRepository
     */
    protected $repository;

    /**
     * @var \App\Core\Services\AddressService
     */
    protected $service;

    protected function _before()
    {
        $this->dm = $this->tester->getDocumentManager();
        $this->repository = $this->dm->getRepository(\App\Core\Dictionary\Entities\Country::class);
        $this->service = new \App\Core\Services\AddressService($this->repository);
    }

    protected function _after()
    {
    }

    // tests
    public function testBuildsAddress()
    {
        /** @var \App\Core\Dictionary\Entities\Country[] $countries */
        $countries = $this->repository->findAll();
        $countryID = $countries[0]->getId();
//        $correctAddress = '603000, Нижний Новогоро, Волжская Набережная, 9, 180';
//        $coordinates = [
//            'lat' => 56.345365,
//            'lng' => 43.9385753,
//        ];
        $address = $this->service->build($countryID);
        $this->tester->assertInstanceOf(\App\Core\ValueObjects\Address::class, $address);
    }

    public function testThrowsExceptions()
    {
        $countries = $this->repository->findAll();
        $countryID = $countries[0]->getId();
        $wrongID = $countryID.'a';
        $correctAddress = '603000, Нижний Новогоро, Волжская Набережная, 9, 180';
        $wrongAddress = '';
        $coordinates = [
            'lat' => 56.345365,
            'lng' => 43.9385753,
        ];
        $wrongCoordinates = [
            'lat' => 56.345365,
            'longitude' => 0.123,
        ];
        $this->tester->expectException(\InvalidArgumentException::class, function () use ($wrongAddress, $countryID, $coordinates) {
            $this->service->build($wrongAddress, $countryID, $coordinates);
        });
        $this->tester->expectException(\InvalidArgumentException::class, function () use ($correctAddress, $wrongID, $coordinates) {
            $this->service->build($correctAddress, $wrongID, $coordinates);
        });
        $this->tester->expectException(\InvalidArgumentException::class, function () use ($correctAddress, $countryID, $wrongCoordinates) {
            $this->service->build($correctAddress, $countryID, $wrongCoordinates);
        });
    }
}
