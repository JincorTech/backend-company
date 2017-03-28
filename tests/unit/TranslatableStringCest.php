<?php

use App\Core\ValueObjects\TranslatableString;
use Faker\Factory;

class TranslatableStringCest
{

    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create(config('app.locale'));
    }

    /**
     * Allows to create correct instances
     *
     * @param UnitTester $I
     */
    public function canCreateCorrect(UnitTester $I)
    {
        $str = TranslatableStringFactory::make();
        $I->assertNotNull($str->getValue());
    }

    /**
     * Don't allows to create wrong instances without default locale value
     *
     * @param UnitTester $I
     */
    public function testCannotCreateWrong(UnitTester $I)
    {
        $I->expectException(InvalidArgumentException::class, function () {
            $str = new TranslatableString([
                'it' => 'sdsd',
                'fr' => 'sdssd',
            ]);
        });
    }


    /**
     * Test if we can get any values
     *
     * @param UnitTester $I
     */
    public function testGetValue(UnitTester $I)
    {
        $str = TranslatableStringFactory::make();
        $I->assertNotNull($str->getValue());
        $I->assertNotNull($str->getValue('fr'));
        $I->assertNotNull($str->getValue('ru'));
    }

    /**
     * Try to add some value and get it later
     * @param UnitTester $I
     */
    public function testAddValue(UnitTester $I)
    {
        $str = TranslatableStringFactory::make();
        $str->setValue('lt', 'Some lt value');
        $I->assertEquals('Some lt value', $str->getValue('lt'));
    }

}
