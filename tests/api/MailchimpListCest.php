<?php

use App\Core\Services\Mailing\Lists\MailchimpListService;
use App\Core\Services\Mailing\Lists\MailingListServiceInterface;

require(__DIR__ . '/MailgunMailingListCest.php');

class MailchimpListCest extends MailgunMailingListCest
{
    public function _before(ApiTester $I)
    {
        putenv('MAILING_LIST_DRIVER=mailchimp');
        $I->haveBinding(MailingListServiceInterface::class, MailchimpListService::class);
    }
}
