<?php

use App\Domains\Company\ValueObjects\CompanyExternalLink;

class CompanyExternalLinkCest
{

    /**
     * Ensure we can create links which contains correct urls
     * @param UnitTester $I
     */
    public function allowsCreateCorrect(UnitTester $I)
    {
        $name = 'test';
        $link = 'https://facebook.com/test';
        $url = new CompanyExternalLink($link);
        $I->assertEquals($name, $url->getName());
        $I->assertEquals($link, $url->getUrl());
    }

    /**
     * Ensure we don't allow to create instances with
     * wrong data
     *
     * @param UnitTester $I
     */
    public function notAllowsCreateWrong(UnitTester $I)
    {
        $I->expectException(InvalidArgumentException::class, function(){
            $name = 'test';
            $link = 'htps://facebook.com/test';
            new CompanyExternalLink($link);
        });

        $I->expectException(InvalidArgumentException::class, function(){
            $name = 'test';
            $link = 'http://facebook';
            new CompanyExternalLink($link);
        });
    }
}
