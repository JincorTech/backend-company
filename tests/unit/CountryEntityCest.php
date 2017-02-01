<?php

use App\Core\ValueObjects\CountryISOCodes;
use App\Core\Dictionary\Entities\Country;
use App\Core\Dictionary\Entities\Currency;
use GeoJson\Geometry\MultiPolygon;

class CountryEntityCest
{
    /**
     * @var CountryISOCodes
     */
    public $countryISO;

    /**
     * @var array
     */
    public $names;

    /**
     * @var \App\Core\Dictionary\Entities\Currency
     */
    public $currency;

    /**
     * @var Country
     */
    public $object;

    /**
     * @var MultiPolygon
     */
    public $bounds;

    public function _before(UnitTester $I)
    {
        $this->countryISO = new CountryISOCodes('ISO 3166-2:AU', 36, 'AU', 'AUS');
        $this->names = [
            'en' => 'Australia',
            'ru' => 'Австралия',
        ];
        $this->bounds = new MultiPolygon($I->getBoundsOfRussia());
        $currencyCodes = new \App\Core\ValueObjects\CurrencyISOCodes('AUD', 36);
        $this->currency = new Currency([
            'en' => 'Australian dollar',
            'ru' => 'Австралийский доллар',
        ], $currencyCodes, '$');
        $this->object = new Country($this->names, '+61', $this->countryISO, $this->currency, 'flag.png', $this->bounds);
    }

    public function _after(UnitTester $I)
    {
    }

    public function createsUUIDTest(UnitTester $I)
    {
        $I->assertNotNull($this->object->getId());
    }

    /**
     * @param UnitTester $I
     * @covers App\Domains\Dictionary\Entities\Country::getNumericCode
     */
    public function getCountryNumericCodeTest(UnitTester $I)
    {
        $I->assertEquals($this->countryISO->getNumericCode(), $this->object->getNumericCode());
    }

    /**
     * @param UnitTester $I
     * @covers App\Domains\Dictionary\Entities\Country::getAlpha2Code
     */
    public function getCountryAlpha2CodeTest(UnitTester $I)
    {
        $I->assertEquals($this->countryISO->getAlpha2Code(), $this->object->getAlpha2Code());
    }

    /**
     * @param UnitTester $I
     * @covers App\Domains\Dictionary\Entities\Country::getAlpha3Code
     */
    public function getCountryAlpha3CodeTest(UnitTester $I)
    {
        $I->assertEquals($this->countryISO->getAlpha3Code(), $this->object->getAlpha3Code());
    }

    /**
     * @param UnitTester $I
     * @covers App\Domains\Dictionary\Entities\Country::getCurrency
     */
    public function getCountryCurrency(UnitTester $I)
    {
        $I->assertEquals($this->currency, $this->object->getCurrency());
    }

    /**
     * @param UnitTester $I
     * @covers App\Domains\Dictionary\Entities\Country::getBounds
     */
    public function countryBoundsTest(UnitTester $I)
    {
        $I->assertInstanceOf(MultiPolygon::class, $this->object->getBounds());
        $I->assertEquals($this->bounds, $this->object->getBounds());
    }

    /**
     * @param UnitTester $I
     * @covers App\Domains\Dictionary\Entities\Country::getName
     */
    public function getCountryNameTest(UnitTester $I)
    {
        $I->assertEquals($this->names['ru'], $this->object->getName('ru'));
        $I->assertEquals($this->names['en'], $this->object->getName('en'));
        $I->assertEquals($this->names['en'], $this->object->getName('th'));
        $I->assertEquals($this->names['en'], $this->object->getName());
    }

    /**
     * @param UnitTester $I
     * @covers App\Domains\Dictionary\Entities\Country::setPhoneCode
     */
    public function setCountryNamesTest(UnitTester $I)
    {
        $names = [
            'ru' => 'Австралия',
        ];
        $I->expectException(\InvalidArgumentException::class, function () use ($names) {
            new Country($names, '+61', $this->countryISO, $this->currency, 'flag.png', $this->bounds);
        });
        $names['en'] = 'Australia';
        new Country($names, '+61', $this->countryISO, $this->currency, 'flag.png', $this->bounds);
    }

    /**
     * @param UnitTester $I
     * @covers App\Domains\Dictionary\Entities\Country::setPhoneCode
     */
    public function setCountryPhoneCodeTest(UnitTester $I)
    {
        $I->expectException(\InvalidArgumentException::class, function () {
            new Country($this->names, '', $this->countryISO, $this->currency, 'flag.png', $this->bounds);
        });
        $I->expectException(\InvalidArgumentException::class, function () {
            new Country($this->names, '61', $this->countryISO, $this->currency, 'flag.png', $this->bounds);
        });
        $I->expectException(\InvalidArgumentException::class, function () {
            new Country($this->names, '+', $this->countryISO, $this->currency, 'flag.png', $this->bounds);
        });
        new Country($this->names, '+61', $this->countryISO, $this->currency, 'flag.png', $this->bounds);
    }

    /**
     * @param UnitTester $I
     * @covers App\Domains\Dictionary\Entities\Country::setFlagUrl
     */
    public function setCountryFlagUrlTest(UnitTester $I)
    {
        $I->expectException(\InvalidArgumentException::class, function () {
            new Country($this->names, '+61', $this->countryISO, $this->currency, '', $this->bounds);
        });
        new Country($this->names, '+61', $this->countryISO, $this->currency, 'flag.png', $this->bounds);
    }
}
