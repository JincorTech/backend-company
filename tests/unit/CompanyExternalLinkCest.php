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
        $link = 'https://facebook.com/test';
        $url = new CompanyExternalLink($link);
        $I->assertEquals('facebook.com', $url->getName());
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
            $link = 'htps://facebook.com/test';
            new CompanyExternalLink($link);
        });

        $I->expectException(InvalidArgumentException::class, function(){
            $link = 'http://facebook';
            new CompanyExternalLink($link);
        });
    }

    public function getDomainFalse(UnitTester $I)
    {
        $link = 'http://192.168.1.1';
        $externalLink = new CompanyExternalLink($link);
        $I->assertFalse($externalLink->getDomain($link));
    }
}
