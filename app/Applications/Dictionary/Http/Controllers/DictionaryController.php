<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/18/16
 * Time: 8:25 PM
 */

namespace App\Applications\Dictionary\Http\Controllers;

use App\Applications\Dictionary\Transformers\Registration\CountryTransformer;
use App\Applications\Dictionary\Transformers\CountryListSchemaTransformer;
use App\Applications\Dictionary\CriteriaBuilders\CountryCriteriaBuilder;
use App\Applications\Dictionary\Http\Requests\ListCountriesRequest;
use App\Applications\Dictionary\Http\Requests\ListCitiesRequest;
use App\Core\Dictionary\Repositories\CountryRepository;
use App\Core\Dictionary\Entities\Country;
use Doctrine\ODM\MongoDB\DocumentManager;
use Illuminate\Support\Collection;
use App\Core\Dictionary\Entities\City;
use App\Applications\Dictionary\Transformers\CityTransformer;
use App;

class DictionaryController extends BaseController
{
    /**
     * @var CountryRepository
     */
    private $countryRepository;

    /**
     * @var App\Core\Dictionary\Repositories\CityRepository
     */
    private $cityRepository;

    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * DictionaryController constructor.
     */
    public function __construct()
    {
        $this->dm = App::make(DocumentManager::class);
        $this->countryRepository = $this->dm->getRepository(Country::class);
        $this->cityRepository = $this->dm->getRepository(City::class);

        parent::__construct();
    }

    /**
     * Lists countries according to request.
     *
     * @param ListCountriesRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listCountries(ListCountriesRequest $request)
    {
        $countriesCollection = new Collection(
            $this->countryRepository->findBy(CountryCriteriaBuilder::fromRequest($request))
        );

        return $this->response->collection($countriesCollection, new CountryTransformer());
    }


    /**
     * @param ListCitiesRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function listCities(ListCitiesRequest $request)
    {
        $country = $this->countryRepository->find($request->getCountryId());

        if (!$country) {
            $this->response->errorNotFound(trans('exceptions.country.not_found'));
        }

        $citiesCollection = new Collection(
            $this->cityRepository->findInCountry($country)
        );

        return $this->response->collection($citiesCollection, CityTransformer::class);
    }

    /**
     * Returns table schema to build tables on frontend.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function countriesListSchema()
    {
        return $this->response->item(new \StdClass(), new CountryListSchemaTransformer());
    }
}
