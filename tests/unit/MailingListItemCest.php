<?php

use App\Core\Services\Mailing\Lists\MailgunListService;
use App\Core\Services\Mailing\Lists\MailingListServiceInterface;
use App\Core\ValueObjects\MailingListItem;
use App\Core\Exceptions\UnknownMailingListId;

class MailingListItemCest
{
    public function _before(UnitTester $I)
    {
        putenv('MAILING_LIST_DRIVER=mailgun');
        app()->bind(MailingListServiceInterface::class, MailgunListService::class);
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

    public function canCreate(UnitTester $I)
    {
        $email = 'ortgma@gmail.com';
        $item = new MailingListItem($email, 'ico@jincor.com');

        $I->assertEquals($email, $item->getEmail());
        $I->assertEquals('ico@jincor.com', $item->getMailingListId());
    }
}
