<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 04/05/2017
 * Time: 12:50
 */

namespace App\Applications\Company\Console\Commands;

use App\Domains\Company\Search\CompanyIndexContract;
use Illuminate\Console\Command;
use Elasticsearch;

class BuildIndex extends Command implements CompanyIndexContract
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:index:company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build company index';


    public function handle()
    {
        $params = [
            'index' => self::INDEX,
//            'type' => self::TYPE,
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 2
                ],
                'mappings' => [
                    self::TYPE => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'dynamic_templates' => [
                            ['en' => [
                                'match' => '*en',
                                'match_mapping_type' => 'string',
                                'mapping' => [
                                    'type' => 'string',
                                    'analyzer' => 'english'
                                ]
                            ]],
                            ['ru' => [
                                'match' => '*ru',
                                'match_mapping_type' => 'string',
                                'mapping' => [
                                    'type' => 'string',
                                    'analyzer' => 'russian'
                                ]
                            ]],
                        ],
                        'properties' => [
                            'legalName' => [
                                'type' => 'string',
                                'analyzer' => 'standard'
                            ],
                            'country' => [
                                'type' => 'string',
                                'index' => 'not_analyzed'
                            ],
                            'city' => [
                                'type' => 'string',
                                'index' => 'not_analyzed'
                            ],
                        ]
                    ]
                ],
            ]
        ];

//        dd(Elasticsearch::connection()->indices()->create($params));
        dd(Elasticsearch::connection()->indices()->delete($params));
    }
}