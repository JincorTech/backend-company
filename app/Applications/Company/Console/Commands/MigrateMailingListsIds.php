<?php

namespace App\Applications\Company\Console\Commands;

use Illuminate\Console\Command;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Core\Services\Mailing\Lists\MailgunListService;
use App\Core\Services\Mailing\Lists\MailchimpListService;
use App\Core\ValueObjects\MailingListItem;
use App;

class MigrateMailingListsIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:mailing:lists:ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing mailing list IDs to mailchimp IDs';

    public function handle()
    {
        /**
         * @var $dm DocumentManager
         */
        $dm = App::make(DocumentManager::class);

        $mailgunService = new MailgunListService();
        $mailchimpService = new MailchimpListService();
        foreach ($mailgunService->getMailingLists() as $subject => $id) {

            $dm->createQueryBuilder(MailingListItem::class)
                ->updateMany()
                ->field('mailingListId')->set($mailchimpService->getMailingLists()[$subject])
                ->field('mailingListId')->equals($id)
                ->getQuery()
                ->execute();
        }
    }
}
