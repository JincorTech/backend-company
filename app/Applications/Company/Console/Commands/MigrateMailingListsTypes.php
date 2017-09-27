<?php

namespace App\Applications\Company\Console\Commands;

use Illuminate\Console\Command;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Core\ValueObjects\MailingListItem;
use App;

class MigrateMailingListsTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:mailing:lists:types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing mailing list item types';

    public function handle()
    {
        /**
         * @var $dm DocumentManager
         */
        $dm = App::make(DocumentManager::class);

        $dm->createQueryBuilder(MailingListItem::class)
            ->updateMany()
            ->field('type')->set('standard')->exists(false)
            ->getQuery()
            ->execute();
    }
}
