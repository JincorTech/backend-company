<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 24/04/2017
 * Time: 01:03
 */

namespace App\Applications\Company\Console\Commands;


use App\Domains\Company\Search\CompanyIndexContract;
use App\Applications\Company\Services\Company\CompanyService;
use Illuminate\Console\Command;
use Elasticsearch;

class GetIndex extends Command implements CompanyIndexContract
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:show {collection} {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test index searching';

    /**
     * @var CompanyService
     */
    protected $service;

    public function __construct(CompanyService $companyService)
    {
        parent::__construct();
        $this->service = $companyService;
    }

    public function handle()
    {
        dd(Elasticsearch::connection()->search([
            'index' => $this->argument('collection'),
            'type' => $this->argument('type'),
//            'body' => [
//                'query' => [
//                    'bool' => [
//                        'must' => [
//                            'multi_match' => [
//                                'query' => 'Урал',
//                                'fields'=> ['legalName', 'description', 'companyType*', 'economicalActivities*'],
//                                'type' => 'cross_fields'
//                            ]
//                        ],
//                        'filter' => [
//                            'bool' => [
//                                'must' => [
//                                    ['term' => ['country' => 'd169ea21-c226-4c85-847b-ef4031408541',]],
//                                    ['term' => ['eActivityIds' => '283a21b9-efbc-4f13-a37c-2c28077690f9',]],
//                                ]
//                            ]
//                        ]
//                    ],
//                ]
//            ]
        ]));
    }


}