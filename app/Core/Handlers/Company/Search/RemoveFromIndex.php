<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 02/05/2017
 * Time: 18:57
 */

namespace App\Core\Handlers\Company\Search;


use App\Domains\Company\Events\BaseCompanyEvent;
use App\Domains\Company\Search\CompanyIndexContract;
use Elasticsearch;

class RemoveFromIndex implements CompanyIndexContract
{

    public function handle(BaseCompanyEvent $event)
    {
        try {
            Elasticsearch::connection()->delete([
                'index' => self::INDEX,
                'type' => self::TYPE,
                'id' => $event->getCompany()->getId(),
            ]);
        } catch (\Exception $e) {
            if (env('APP_ENV') === 'local') {
                return;
            } else {
                throw $e;
            }
        }
    }

}