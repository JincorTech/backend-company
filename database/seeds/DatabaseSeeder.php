<?php

use Illuminate\Database\Seeder;
use App\Core\Dictionary\Entities\Currency;
use App\Core\Dictionary\Entities\Country;
use App\Domains\Company\Entities\CompanyType;
use App\Domains\Company\Entities\EconomicalActivityType;
use Doctrine\ODM\MongoDB\DocumentManager;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->getDm()->getDocumentDatabase(Currency::class)->drop();
        $this->getDm()->getDocumentDatabase(Country::class)->drop();
        $this->getDm()->getDocumentDatabase(CompanyType::class)->drop();
        $this->getDm()->getDocumentDatabase(EconomicalActivityType::class)->drop();
        $this->call(CurrencySeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(CompanyTypeSeeder::class);
        $this->call(CompanyActivityTypes::class);
        $this->call(CompanySeeder::class);
        // $this->call(UsersTableSeeder::class);
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function getDm()
    {
        return $this->container->make(DocumentManager::class);
    }


}
