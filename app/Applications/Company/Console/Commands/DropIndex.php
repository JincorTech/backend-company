<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 25/05/2017
 * Time: 11:41
 */

namespace App\Applications\Company\Console\Commands;


use App\Domains\Company\Search\CompanyIndexContract;
use Illuminate\Console\Command;
use Elasticsearch;

class DropIndex extends Command implements CompanyIndexContract
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:drop:company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop company index';

    public function handle()
    {
        Elasticsearch::connection()->indices()->delete([
            'index' => self::INDEX,
        ]);
    }


}