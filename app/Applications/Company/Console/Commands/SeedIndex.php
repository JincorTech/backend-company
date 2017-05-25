<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 03/05/2017
 * Time: 14:02
 */

namespace App\Applications\Company\Console\Commands;

use App\Domains\Company\Repositories\EconomicalActivityRepository;
use App\Domains\Company\Entities\EconomicalActivityType;
use App\Core\Dictionary\Repositories\CountryRepository;
use App\Domains\Company\Search\CompanyIndexContract;
use App\Domains\Company\Services\CompanyService;
use App\Domains\Company\Entities\CompanyType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentRepository;
use App\Core\Dictionary\Entities\Country;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Core\ValueObjects\Address;
use Faker\Generator;
use Illuminate\Console\Command;
use Faker\Factory;
use Elasticsearch;
use App;

class SeedIndex extends Command implements CompanyIndexContract
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild search index for specified collection(' . self::INDEX . ')';

    /**
     * @var CompanyService
     */
    protected $service;

    /**
     * @var DocumentManager|mixed
     */
    protected $dm;

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var array
     */
    protected $activities;

    /**
     * @var array
     */
    protected $types;


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
            event(new App\Domains\Company\Events\CompanyAdded($company));
        }
    }

    private function makeAddress(Country $country, Generator $faker)
    {
        $cities = $this->dm->getRepository(App\Core\Dictionary\Entities\City::class)->findInCountry($country)->toArray();
        try {
            $city = $cities[array_rand($cities)];
        } catch (\Exception $e){
            dd($country->getAlpha2Code());
        }
        return new Address($faker->streetAddress, $country, $city);
    }

}