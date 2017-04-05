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

    public function testGetChildren(UnitTester $I)
    {
        $type = EconomicalActivityTypeFactory::make();
        $parent = EconomicalActivityTypeFactory::make();
        $type->setParent($parent);
        $parent->children->add($type);
        $I->assertInstanceOf(\Doctrine\Common\Collections\ArrayCollection::class, $parent->getChildren());
    }

}
