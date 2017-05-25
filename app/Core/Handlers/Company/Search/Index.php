<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 02/05/2017
 * Time: 18:58
 */

namespace App\Core\Handlers\Company\Search;


use App\Domains\Company\Entities\EconomicalActivityType;
use App\Domains\Company\Events\BaseCompanyEvent;
use App\Domains\Company\Search\CompanyIndexContract;
use Elasticsearch;

class Index implements CompanyIndexContract
{

    public function handle(BaseCompanyEvent $event)
    {
        $fields = [
            'legalName' => $event->getCompany()->getProfile()->getName(),
            'description' => $event->getCompany()->getProfile()->getDescription(),
        ];
        if ($event->getCompany()->getProfile()->getType() && !empty($event->getCompany()->getProfile()->getType()->getNames())) {
            foreach ($event->getCompany()->getProfile()->getType()->getNames()->getValues() as $loc => $type) {
                $fields['companyType'.$loc] = $type;
            }
        }

        $fields['eActivityIds'] = [];
        /** @var EconomicalActivityType $economicalActivity */
        foreach ($event->getCompany()->getProfile()->getEconomicalActivities() as $economicalActivity) {
            $fields['eActivityIds'][] = $economicalActivity->getId();

            foreach ($economicalActivity->getNames()->getValues() as $l => $activity) {
                if (!array_key_exists('economicalActivities'.$l, $fields)) {
                    $fields['economicalActivities'.$l] = $activity;
                } else {
                    $fields['economicalActivities'.$l] .= ', ' . $activity;
                }
            }
        }
        $fields['country'] = $event->getCompany()->getProfile()->getAddress()->getCountry()->getId();

        if ($city = $event->getCompany()->getProfile()->getAddress()->getCity()) {
            $fields['city'] = $city->getId();
        }

        if ($event->getCompany()->getProfile()->getBrandName() && $event->getCompany()->getProfile()->getBrandName()->getValues()) {
            foreach ($event->getCompany()->getProfile()->getBrandName()->getValues() as $locale => $brandName) {
                $fields['brandName'.$locale] = $brandName;
            }
        }
        try {
            Elasticsearch::connection()->index([
                'index' => self::INDEX,
                'type' => self::TYPE,
                'id' => $event->getCompany()->getId(),
                'body' => $fields,
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