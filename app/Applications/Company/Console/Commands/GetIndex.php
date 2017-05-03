<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 24/04/2017
 * Time: 01:03
 */

namespace App\Applications\Company\Console\Commands;


use App\Domains\Company\Services\CompanyService;
use Illuminate\Console\Command;
use Elasticsearch;

class GetIndex extends Command
{

    const COMPANIES = 'companies';

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
    protected $description = 'Rebuild search index for specified collection(' . self::COMPANIES . ')';

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
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'multi_match' => [
                                'query' => 'Jincor Company',
                                'fields'=> ['legalName', 'description', 'companyType*', 'economicalActivities*'],
                                'type' => 'cross_fields'
                            ]
                        ],
                        'filter' => [
                            'term' => [
                                'country' => 'ed680733-6f75-4273-8a65-de0c0517b056'
                            ]
                        ]
                    ],
                ]
            ]
        ]));
    }


}