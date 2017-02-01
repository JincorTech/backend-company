<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Factory extends \Codeception\Module
{
    /**
     * @var \League\FactoryMuffin\FactoryMuffin
     */
    private $factory;

    private $faker;

    public function _initialize()
    {
        $this->factory = new \League\FactoryMuffin\FactoryMuffin();
//        $this->faker = \Faker\Factory::create();
//        $this->factory->define('App\Core\ValueObjects\CountryISOCodes')->setDefinitions([
////            'alpha2Code'    => $this->faker->, // title with random 5 words
//            'alpha3Code'    => 'sentence|5', // title with random 5 words
//            'numericCode'   => 'text', // random text
//            'ISO2Code' => 'text'
//        ]);
    }
}
