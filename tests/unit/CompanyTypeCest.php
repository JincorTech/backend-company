<?php

use App\Domains\Company\Entities\CompanyType;


class CompanyTypeCest
{
    private $ru = 'Some other title';
    private $en = 'Some title';
    private $code = 'AAA';

    /**
     *
     * @param UnitTester $I
     */
    public function canCreateInstance(UnitTester $I)
    {
       $I->wantTo('Create an instance and check if it works as expected');
        $type = new CompanyType([
            'en' => $this->en,
            'ru' => $this->ru,
        ], $this->code);
        $I->assertEquals($type->getName('ru'), $this->ru);
        $I->assertEquals($type->getName('en'), $this->en);
        $I->assertEquals($type->getCode(), $this->code);
        $I->assertNotEmpty($type->getId());
    }
}
