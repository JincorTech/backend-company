<?php
use App\Core\ValueObjects\MailingListItem;
use App\Core\Exceptions\UnknownMailingListId;

class MailingListItemCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    public function testConstructInvalidEmail(UnitTester $I)
    {
        $I->expectException(InvalidArgumentException::class, function () {
            $notValidEmail = 'not.valid.email';
            new MailingListItem($notValidEmail, 'ico');
        });
    }

    public function testConstructUnknownMailingListId(UnitTester $I)
    {
        $I->expectException(UnknownMailingListId::class, function () {
            $email = 'valid@email.com';
            new MailingListItem($email, 'ico1');
        });
    }

    public function canCreate(UnitTester $I)
    {
        $email = 'ortgma@gmail.com';
        $item = new MailingListItem($email, 'ico');

        $I->assertEquals($email, $item->getEmail());
        $I->assertEquals('ico@jincor.com', $item->getMailingListId());
    }
}
