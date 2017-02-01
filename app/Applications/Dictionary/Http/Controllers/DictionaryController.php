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
use App\Applications\Dictionary\CriteriaBuilders\CountryCriteriaBuilder;
use App\Applications\Dictionary\Http\Requests\ListCountriesRequest;
use App\Applications\Dictionary\Transformers\CountryListSchemaTransformer;
use App\Core\Dictionary\Repositories\CountryRepository;
use App\Core\Dictionary\Entities\Country;
use Doctrine\ODM\MongoDB\DocumentManager;
use Illuminate\Support\Collection;
use App;

class DictionaryController extends BaseController
{
    /**
     * @var CountryRepository
     */
    private $countryRepository;

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
     * Returns table schema to build tables on frontend.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function countriesListSchema()
    {
        return $this->response->item(new \StdClass(), new CountryListSchemaTransformer());
    }
}
