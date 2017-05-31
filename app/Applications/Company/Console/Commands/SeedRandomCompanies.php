<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 25/05/2017
 * Time: 16:36
 */

namespace App\Applications\Company\Console\Commands;

use App\Domains\Company\Entities\EconomicalActivityType;
use App\Domains\Company\Services\CompanyService;
use App\Domains\Company\Entities\CompanyType;
use App\Core\Dictionary\Entities\Country;
use Doctrine\ODM\MongoDB\DocumentManager;
use Illuminate\Console\Command;
use Faker\Generator;
use Faker\Factory;
use App;

class SeedRandomCompanies extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed random companies';

    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var CompanyService
     */
    private $service;

    /**
     * @var App\Core\Dictionary\Repositories\CountryRepository
     */
    private $countryRepository;

    /**
     * @var array
     */
    private $types;

    /**
     * @var array
     */
    private $activities;


    public function __construct(CompanyService $companyService)
    {
        parent::__construct();
        $this->service = $companyService;
        $this->dm = App::make(DocumentManager::class);
        $this->countryRepository = $this->dm->getRepository(Country::class);
        $this->types = $this->dm->getRepository(CompanyType::class)->findAll();
        $this->activities = $this->dm->getRepository(EconomicalActivityType::class)->findAll();

    }


    public function handle()
    {
        $faker = Factory::create('ru_RU');
        $companies = [];
        for ($i = 0; $i < 200; $i++) {
            $name = $faker->company;
            if ($i % 2 === 0) {
                $country = $this->countryRepository->findByAlpha2Code('RU');
            } else {
                $country = $this->countryRepository->findByAlpha2Code('US');
            }
            $address = $this->makeAddress($country, $faker);
            $type = $this->types[array_rand($this->types)];
            $company = new App\Domains\Company\Entities\Company($name, $address, $type);
            $keys = array_rand($this->activities, rand(1, 3));
            $activities = [];
            if (is_array($keys)) {
                foreach ($keys as $key) {
                    $activities[] = $this->activities[$key];
                }
            } else {
                $activities[] = $this->activities[$keys];
            }
            $company->getProfile()->setEconomicalActivities($activities);
            $this->dm->persist($company);
            array_push($companies, $company);
            event(new App\Domains\Company\Events\CompanyAdded($company));
        }
        $this->dm->flush($companies);
    }


    private function makeAddress(Country $country, Generator $faker)
    {
        $cities = $this->dm->getRepository(App\Core\Dictionary\Entities\City::class)->findInCountry($country)->toArray();
        try {
            $city = $cities[array_rand($cities)];
        } catch (\Exception $e){
            dd($country->getAlpha2Code());
        }
        return new App\Core\ValueObjects\Address($faker->streetAddress, $country, $city);
    }


}