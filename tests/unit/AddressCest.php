<?php

use App\Core\ValueObjects\Address;

class AddressCest
{


    /**
     * Test we can build addresses using country and formatted address
     *
     * @param UnitTester $I
     */
    public function canMakeAddress(UnitTester $I)
    {
        $country = CountryFactory::make();
        $address = new Address('ул. Пушкина, дом Колотушкина, г. Москва', $country);
        $I->assertInstanceOf(Address::class, $address);
        $I->assertEquals($address->getCountry()->getId(), $country->getId());
        $I->assertEquals('ул. Пушкина, дом Колотушкина, г. Москва', $address->getFormattedAddress());
    }

}
