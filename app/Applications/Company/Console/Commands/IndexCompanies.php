<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 23/04/2017
 * Time: 23:32
 */

namespace App\Applications\Company\Console\Commands;


use App\Domains\Company\Entities\Company;
use App\Domains\Company\Entities\EconomicalActivityType;
use App\Applications\Company\Jobs\Search\IndexCompanies as Job;
use App\Domains\Company\Services\CompanyService;
use Illuminate\Console\Command;
use Elasticsearch;

class IndexCompanies extends Command
{

    const COMPANIES = 'companies';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:index {collection}';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        switch ($this->argument('collection')) {
            case self::COMPANIES:
                $this->indexCompanies();
                break;
        }
    }

    private function indexCompanies()
    {
        $companies = $this->service->repository->findAll();
        dispatch(new Job($companies));
    }

}