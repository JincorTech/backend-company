<?php


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

}
