<?php
use App\Domains\Employee\ValueObjects\EmployeeProfile;

class EmployeeProfileCest
{
    public function canGetNamesAndPosition(UnitTester $I)
    {
        $profile = EmployeeProfileFactory::makeIvan();

        $I->assertEquals('Ivan', $profile->getFirstName());
        $I->assertEquals('Ivanov', $profile->getLastName());
        $I->assertEquals('position', $profile->getPosition());
        $I->assertEquals('Ivan Ivanov', $profile->getName());
    }

    public function canChangeNamesAndPosition(UnitTester $I)
    {
        $profile = EmployeeProfileFactory::makeIvan();

        $profile->changeFirstName('Petr');
        $I->assertEquals('Petr', $profile->getFirstName());

        $profile->changeLastName('Petrov');
        $I->assertEquals('Petrov', $profile->getLastName());

        $profile->changePosition('newposition');
        $I->assertEquals('newposition', $profile->getPosition());
    }

    public function canSetGetUnsetAvatar(UnitTester $I)
    {
        $profile = EmployeeProfileFactory::makeIvan();

        $url = 'http://avatar.com';

        $profile->setAvatar($url);
        $I->assertEquals($url, $profile->getAvatar());

        $profile->unsetAvatar();
        $I->assertNull($profile->getAvatar());
    }
}
