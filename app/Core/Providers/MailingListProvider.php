<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 16:41
 */

namespace App\Core\Providers;
use Illuminate\Support\ServiceProvider;
use App\Core\Interfaces\MailingListRepositoryInterface;
use App\Core\ValueObjects\MailingListItem;
use Doctrine\ODM\MongoDB\DocumentManager;
use App;

class MailingListProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->instance(
            MailingListRepositoryInterface::class,
            $this->app->make(DocumentManager::class)->getRepository(MailingListItem::class)
        );
    }
}
