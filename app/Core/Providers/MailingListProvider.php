<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 16:41
 */

namespace App\Core\Providers;
use App\Core\Services\Mailing\Lists\MailingListManager;
use App\Core\Services\Mailing\Lists\MailingListServiceInterface;
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

        $this->app->alias('mailinglist.driver', MailingListServiceInterface::class);
        $this->app->singleton('mailinglist', function ($app) {
            $app['mailinglist.loaded'] = true;
            return new MailingListManager($app);
        });
        $this->app->singleton('mailinglist.driver', function ($app) {
            return $app['mailinglist']->driver();
        });
    }
}
