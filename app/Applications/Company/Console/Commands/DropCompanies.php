<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 31/05/2017
 * Time: 16:58
 */

namespace App\Applications\Company\Console\Commands;

use App\Domains\Company\Services\CompanyService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Illuminate\Console\Command;
use Artisan;
use App;

class DropCompanies extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:drop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop companies ';

    /**
     * @var DocumentManager
     */
    private $dm;

    public function __construct()
    {
        parent::__construct();
        $this->dm = App::make(DocumentManager::class);
    }


    public function handle()
    {
        $this->dm->getDocumentCollection(App\Domains\Company\Entities\Company::class)->drop();
        Artisan::call('search:drop:company');
        Artisan::call('search:index:company');
    }

}