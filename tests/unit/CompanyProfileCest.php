<?php


use App\Domains\Company\ValueObjects\CompanyProfile;
use App\Domains\Company\ValueObjects\CompanyExternalLink;
use App\Domains\Company\Entities\CompanyType;
use Faker\Factory;


class CompanyProfileCest
{

    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('ru_RU');
    }


    /**
     * @param UnitTester $I
     */
    public function canCreateProfile(UnitTester $I)
    {
        $ct = CompanyTypeFactory::make();
        $address = AddressFactory::make();
        $name = $this->faker->company;
        $profile = new CompanyProfile($name, $address, $ct);
        $I->assertEquals($ct, $profile->getType());
        $I->assertEquals($name, $profile->getName());
        $I->assertEquals($address, $profile->getAddress());
    }

    /**
     * Can if we can add some links to profile
     *
     * @param UnitTester $I
     */
    public function canAddLinks(UnitTester $I)
    {
        $profile = new CompanyProfile($this->faker->company, AddressFactory::make(), CompanyTypeFactory::make());
        $links = [
            new CompanyExternalLink('http://test.com'),
            new CompanyExternalLink('http://facebook.com/TestCompany'),
            new CompanyExternalLink('http://twitter.com'),
        ];
        $profile->setLinks($links);
        $I->assertEquals($profile->getLinks()->get(0), $links[0]);
        $I->assertTrue($profile->getLinks()->count() === 3);
    }

    /**
     * Test we can remove some links
     *
     * @param UnitTester $I
     */
    public function canRemoveLinks(UnitTester $I)
    {
        $profile = new CompanyProfile($this->faker->company, AddressFactory::make(), CompanyTypeFactory::make());
        $links = [
            new CompanyExternalLink('http://test.com'),
            new CompanyExternalLink('http://facebook.com/TestCompany'),
            new CompanyExternalLink('http://twitter.com'),
        ];
        $profile->setLinks($links);
        $I->assertEquals($profile->getLinks()->get(1), $links[1]);
        $profile->getLinks()->removeElement($links[1]);
        $I->assertNotEquals($links[1], $profile->getLinks()->get(1));
        $I->assertTrue($profile->getLinks()->count() === 2);
    }


    /**
     * Test we can easily change address
     *
     * @param UnitTester $I
     */
    public function canChangeAddress(UnitTester $I)
    {
        $firstAddress = AddressFactory::make();
        $profile = new CompanyProfile($this->faker->company, $firstAddress, CompanyTypeFactory::make());
        $I->assertEquals($firstAddress, $profile->getAddress());
        $profile->changeAddress(AddressFactory::make());
        $I->assertNotEquals($firstAddress, $profile->getAddress());
    }

    /**
     * Test we can assign many economical activities to the profile
     *
     * @param UnitTester $I
     */
    public function canSetEconomicalActivity(UnitTester $I)
    {
        $profile = new CompanyProfile($this->faker->company, AddressFactory::make(), CompanyTypeFactory::make());
        $economicalActivities = [
            EconomicalActivityTypeFactory::make(),
            EconomicalActivityTypeFactory::make(),
            EconomicalActivityTypeFactory::make(),
        ];
        $profile->setEconomicalActivities($economicalActivities);
        $I->assertEquals($economicalActivities[1], $profile->getEconomicalActivities()->get(1));
    }

    /**
     * Test we can remove economical activities when need
     *
     * @param UnitTester $I
     */
    public function canRemoveEconomicalActivity(UnitTester $I)
    {
        $profile = new CompanyProfile($this->faker->company, AddressFactory::make(), CompanyTypeFactory::make());
        $economicalActivities = [
            EconomicalActivityTypeFactory::make(),
            EconomicalActivityTypeFactory::make(),
            EconomicalActivityTypeFactory::make(),
        ];
        $profile->setEconomicalActivities($economicalActivities);
        $I->assertEquals($economicalActivities[1], $profile->getEconomicalActivities()->get(1));
        $profile->getEconomicalActivities()->removeElement($economicalActivities[1]);
        $I->assertNotEquals($economicalActivities[1], $profile->getEconomicalActivities()->get(1));
    }

    /**
     * Make sure we can add brand name in multiple languages
     *
     * @param UnitTester $I
     */
    public function canAddBrandNames(UnitTester $I)
    {
        $en = 'Test Company';
        $ru = 'Тестовая компания';
        $profile = new CompanyProfile($this->faker->company, AddressFactory::make(), CompanyTypeFactory::make());
        $profile->setBrandNames([
            'en' => $en,
            'ru' => $ru,
        ]);
        $I->assertEquals($en, $profile->getBrandName()->getValue('en'));
        $I->assertEquals($ru, $profile->getBrandName()->getValue('ru'));

        $newBrandNames = [
            'ru' => 'Новое имя',
            'es' => 'Spanish',
        ];
        $profile->setBrandNames($newBrandNames);

        $expected = [
            'en' => $en,
            'ru' => 'Новое имя',
            'es' => 'Spanish',
        ];
        $I->assertEquals($expected, $profile->getBrandName()->getValues());
    }

    public function canSetGetUnsetPhone(UnitTester $I)
    {
        $companyProfile = CompanyFactory::make()->getProfile();

        $companyProfile->setPhone('+79131111111');
        $I->assertEquals('+79131111111', $companyProfile->getPhone());

        $companyProfile->unsetPhone();
        $I->assertNull($companyProfile->getPhone());
    }

    public function canSetGetUnsetEmail(UnitTester $I)
    {
        $companyProfile = CompanyFactory::make()->getProfile();

        $companyProfile->setEmail('test@test.com');
        $I->assertEquals('test@test.com', $companyProfile->getEmail());

        $companyProfile->unsetEmail();
        $I->assertNull($companyProfile->getEmail());

        $I->expectException(InvalidArgumentException::class, function () use ($companyProfile) {
            $companyProfile->setEmail('123');
        });
    }

    public function canSetGetUnsetPicture(UnitTester $I)
    {
        $companyProfile = CompanyFactory::make()->getProfile();

        $companyProfile->setPicture('http://picture.com');
        $I->assertEquals('http://picture.com', $companyProfile->getPicture());

        $companyProfile->unsetPicture();
        $I->assertNull($companyProfile->getPicture());
    }

    public function canSetGetUnsetDescription(UnitTester $I)
    {
        $companyProfile = CompanyFactory::make()->getProfile();

        $companyProfile->setDescription('New description');
        $I->assertEquals('New description', $companyProfile->getDescription());

        $companyProfile->unsetDescription();
        $I->assertNull($companyProfile->getDescription());
    }

    public function canChangeName(UnitTester $I)
    {
        $companyProfile = CompanyFactory::make()->getProfile();

        $companyProfile->changeName('New name');
        $I->assertEquals('New name', $companyProfile->getName());
    }

    public function canSetGetCompanyType(UnitTester $I)
    {
        $companyProfile = CompanyFactory::make()->getProfile();

        $type = CompanyTypeFactory::make();
        $companyProfile->setCompanyType($type);

        $I->assertEquals($type, $companyProfile->getType());
    }

}
