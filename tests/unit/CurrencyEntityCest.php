<?php

use App\Core\ValueObjects\CurrencyISOCodes;
use App\Core\Dictionary\Entities\Currency;

class CurrencyEntityCest
{
    /**
     * @var \App\Core\ValueObjects\CurrencyISOCodes
     */
    protected $isoCodes;

    /**
     * @var array
     */
    protected $names;

    public function _before(UnitTester $I)
    {
        $this->isoCodes = new CurrencyISOCodes('AUD', 36);
        $this->names = [
            'en' => 'Australian dollar',
            'ru' => 'Австралийский доллар',
        ];
    }

    public function _after(UnitTester $I)
    {
    }

    public function createEntityTest(UnitTester $I)
    {
        $currency = new Currency($this->names, $this->isoCodes, '$');
        $I->assertEquals($this->names['en'], $currency->getName('en'));
        $I->assertEquals($this->names['ru'], $currency->getName('ru'));
        $I->assertEquals($this->isoCodes->getAlpha3(), $currency->getAlpha3Code());
        $I->assertEquals($this->isoCodes->getNumeric(), $currency->getNumericCode());
        $I->assertEquals('$', $currency->getSign());
    }

    public function testGetName(UnitTester $I)
    {
        $currency = new Currency($this->names, $this->isoCodes, '$');
        $I->assertEquals($this->names['en'], $currency->getName('en'));
        $I->assertEquals($this->names[App::getLocale()], $currency->getName('th'));
    }
}
