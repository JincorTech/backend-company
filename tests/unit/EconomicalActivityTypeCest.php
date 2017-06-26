<?php
use Doctrine\Common\Collections\ArrayCollection;
use App\Domains\Company\Entities\EconomicalActivityType;

class EconomicalActivityTypeCest
{


    public function canCreateCorrect(UnitTester $I)
    {
        $type = EconomicalActivityTypeFactory::make();
        $I->assertNotNull($type->getName());
        $I->assertNotNull($type->getCode());
        $I->assertNotNull($type->getId());
        $I->assertNull($type->getParent());
        $I->assertEmpty($type->getChildren());
    }


    public function testParent(UnitTester $I)
    {
        $type = EconomicalActivityTypeFactory::make();
        $parent = EconomicalActivityTypeFactory::make();
        $type->setParent($parent);
        $I->assertEquals($parent, $type->getParent());
    }

    public function testGetChildren(UnitTester $I)
    {
        $type = EconomicalActivityTypeFactory::make();
        $parent = EconomicalActivityTypeFactory::make();
        $type->setParent($parent);
        $parent->children->add($type);
        $I->assertInstanceOf(ArrayCollection::class, $parent->getChildren());
    }

    public function canGetNames(UnitTester $I)
    {
        $names = [
            'ru' => 'Русское имя',
            'en' => 'English name',
        ];

        $code = 'code';
        $type = new EconomicalActivityType($names, $code);

        $I->assertEquals($names, $type->getNames()->getValues());
    }

    public function testMappings(UnitTester $I)
    {
        $type = EconomicalActivityTypeFactory::makeFromDb();

        $I->assertEquals(2, $type->getLevel());
        $I->assertEquals('A-16f02a56-1723-47a3-88ea-daaa648d331d.AA-14585013-89c9-4dba-82e2-71a0efe196e9.', $type->getPath());
        $I->assertInstanceOf(ArrayCollection::class, $type->getChildren());
    }
}
