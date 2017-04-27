<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 27/04/2017
 * Time: 02:06
 */

namespace App\Applications\Company\Jobs\Search;


use App\Core\DoctrineProxies\__CG__\App\Domains\Company\Entities\EconomicalActivityType;
use App\Domains\Company\Entities\Company;
use App\Domains\Company\Services\CompanyService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Elasticsearch;

/**
 * Class IndexCompanies
 * @package App\Applications\Company\Jobs\Search
 */
class IndexCompanies implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * @var CompanyService
     */
    private $service;

    /**
     * @var array
     */
    private $companies;


    /**
     * IndexCompanies constructor.
     * @param array $companies
     */
    public function __construct(array $companies)
    {
        $this->companies = $companies;
    }


    /**
     * @param CompanyService $service
     */
    public function handle(CompanyService $service)
    {
        $this->service = $service;

        $params = ['body' => []];
        /** @var Company $company */
        foreach ($this->companies as $i => $company) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'companies',
                    '_type' => 'company',
                    '_id' => $company->getId()
                ]
            ];

            $fields = [
                'legalName' => $company->getProfile()->getName(),
                'description' => $company->getProfile()->getDescription(),
            ];
            if ($company->getProfile()->getType() && $company->getProfile()->getType()->getNames()->getValues()) {
                foreach ($company->getProfile()->getType()->getNames()->getValues() as $loc => $type) {
                    $fields['companyType'.$loc] = $type;
                }
            }
            /** @var EconomicalActivityType $economicalActivity */
            foreach ($company->getProfile()->getEconomicalActivities() as $economicalActivity) {
                foreach ($economicalActivity->getNames()->getValues() as $l => $activity) {
                    if (!array_key_exists('economicalActivities'.$l, $fields)) {
                        $fields['economicalActivities'.$l] = $activity;
                    } else {
                        $fields['economicalActivities'.$l] .= ', ' . $activity;
                    }
                }
            }
            if ($company->getProfile()->getBrandName() && $company->getProfile()->getBrandName()->getValues()) {
                foreach ($company->getProfile()->getBrandName()->getValues() as $locale => $brandName) {
                    $fields['brandName'.$locale] = $brandName;
                }
            }

            $params['body'][] = $fields;
            // Every 1000 documents stop and send the bulk request
            if ($i % 1000 == 0) {
                $responses = Elasticsearch::connection()->bulk($params);

                // erase the old bulk request
                $params = ['body' => []];

                // unset the bulk response when you are done to save memory
                unset($responses);
            }
        }
        // Send the last batch if it exists
        if (!empty($params['body'])) {
            $responses = Elasticsearch::connection()->bulk($params);
        }
    }

}