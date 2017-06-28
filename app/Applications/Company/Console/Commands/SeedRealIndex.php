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
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Company\Entities\Company;
use App\Domains\Company\Events\CompanyAdded;
use App;

class SeedRealIndex extends Command implements CompanyIndexContract
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:seed:company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed company search index with real data';

    public function handle()
    {
        $companies = App::make(DocumentManager::class)->getRepository(Company::class)->findAll();
        foreach ($companies as $company) {
            event(new CompanyAdded($company));
        }
    }
}
