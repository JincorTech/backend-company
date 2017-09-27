<?php

use App\Core\ValueObjects\ExtendedMailingListItem;

class ExtendedMailingListItemCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    public function testConstructInvalidEmail(UnitTester $I)
    {
        $I->expectException(InvalidArgumentException::class, function () {
            $notValidEmail = 'not.valid.email';
            new ExtendedMailingListItem($notValidEmail, 'ico', []);
        });
    }

    public function canCreateNullCountry(UnitTester $I)
    {
        $email = 'ortgma@gmail.com';
        $item = new ExtendedMailingListItem($email, 'ico@jincor.com', [
            'name' => 'John',
            'company' => 'Jincor',
            'position' => 'HR',
            'country' => null,
            'ip' => '127.0.0.1',
            'browserLanguage' => 'en',
            'landingLanguage' => 'en',
        ]);

        $I->assertEquals(null, $item->getCountry());
    }

    public function canCreate(UnitTester $I)
    {
        $email = 'ortgma@gmail.com';
        $item = new ExtendedMailingListItem($email, 'ico@jincor.com', [
            'name' => 'John',
            'company' => 'Jincor',
            'position' => 'HR',
            'country' => 'US',
            'ip' => '127.0.0.1',
            'browserLanguage' => 'en',
            'landingLanguage' => 'en',
        ]);

        $I->assertEquals('US', $item->getCountry());
        $I->assertEquals('John', $item->getName());
        $I->assertEquals('Jincor', $item->getCompany());
        $I->assertEquals('HR', $item->getPosition());
        $I->assertEquals('127.0.0.1', $item->getIp());
        $I->assertEquals('en', $item->getBrowserLanguage());
        $I->assertEquals('en', $item->getLandingLanguage());
    }
}
