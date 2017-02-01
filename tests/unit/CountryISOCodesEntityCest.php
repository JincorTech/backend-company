<?php

use App\Core\ValueObjects\CountryISOCodes;

class CountryISOCodesEntityCest
{
    /**
     * @var CountryISOCodes
     */
    public $object;

    public $ISO2;

    public $numeric;

    public $alpha2;

    public $alpha3;

    public function _before(UnitTester $I)
    {
        $this->ISO2 = 'ISO 3166-2:AU';
        $this->numeric = 36;
        $this->alpha2 = 'AU';
        $this->alpha3 = 'AUS';
        $this->object = new CountryISOCodes($this->ISO2, $this->numeric, $this->alpha2, $this->alpha3);
    }

    public function _after(UnitTester $I)
    {
        $this->object = null;
    }

    /**
     * @param UnitTester $I
     */
    public function createEqualsTest(UnitTester $I)
    {
        $newObject = new CountryISOCodes($this->ISO2, $this->numeric, $this->alpha2, $this->alpha3);
        $I->assertNotSame($newObject, $this->object);
        $I->assertEquals($newObject, $this->object);
    }

    public function dontAllowCreateWrongTest(UnitTester $I)
    {
        $I->expectException(InvalidArgumentException::class, function () {
            new CountryISOCodes($this->ISO2.'R', $this->numeric, $this->alpha2, $this->alpha3);
        });

        $I->expectException(InvalidArgumentException::class, function () {
            new CountryISOCodes($this->ISO2, 1000, $this->alpha2, $this->alpha3);
        });

        $I->expectException(InvalidArgumentException::class, function () {
            new CountryISOCodes($this->ISO2, -1, $this->alpha2, $this->alpha3);
        });

        $I->expectException(InvalidArgumentException::class, function () {
            new CountryISOCodes($this->ISO2, $this->numeric, $this->alpha2.'R', $this->alpha3);
        });

        $I->expectException(InvalidArgumentException::class, function () {
            new CountryISOCodes($this->ISO2, $this->numeric, $this->alpha2, $this->alpha2);
        });
    }

    /**
     * @param UnitTester $I
     *
     */
    public function gettersTest(UnitTester $I)
    {
        $I->assertEquals($this->ISO2, $this->object->getISO2Code());
        $I->assertEquals($this->numeric, $this->object->getNumericCode());
        $I->assertEquals($this->alpha2, $this->object->getAlpha2Code());
        $I->assertEquals($this->alpha3, $this->object->getAlpha3Code());
    }

    /**
     * @param UnitTester $I
     *
     */
    public function settersTest(UnitTester $I)
    {
        $this->object->setISO2Code('ISO 3166-2:RU');
        $I->assertEquals('ISO 3166-2:RU', $this->object->getISO2Code());
        $this->object->setAlpha2Code('RU');
        $I->assertEquals('RU', $this->object->getAlpha2Code());
        $this->object->setAlpha3Code('RUS');
        $I->assertEquals('RUS', $this->object->getAlpha3Code());
        $this->object->setNumericCode(643);
        $I->assertEquals(643, $this->object->getNumericCode());
    }
}
