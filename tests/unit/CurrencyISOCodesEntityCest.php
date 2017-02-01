<?php


class CurrencyISOCodesEntityCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    public function createEntityTest(UnitTester $I)
    {
        $currencyISO = new \App\Core\ValueObjects\CurrencyISOCodes('AUD', 36);
        $I->assertEquals(36, $currencyISO->getNumeric());
        $I->assertEquals('AUD', $currencyISO->getAlpha3());
    }

    public function dontAllowCreateWrong(UnitTester $I)
    {
        $I->expectException(\InvalidArgumentException::class, function () {
            new \App\Core\ValueObjects\CurrencyISOCodes('AU', 36);
        });
        $I->expectException(\InvalidArgumentException::class, function () {
            new \App\Core\ValueObjects\CurrencyISOCodes('AUDA', 36);
        });
        $I->expectException(\InvalidArgumentException::class, function () {
            new \App\Core\ValueObjects\CurrencyISOCodes('AUD', 0);
        });
        $I->expectException(\InvalidArgumentException::class, function () {
            new \App\Core\ValueObjects\CurrencyISOCodes('AUD', 1000);
        });
    }
}
