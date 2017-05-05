<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/18/16
 * Time: 3:45 PM
 *
 * @var \Dingo\Api\Routing\Router
 */
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    /* @var \Dingo\Api\Routing\Router $api */
    $api->group(['prefix' => 'v1'], function ($api) {
        /* @var \Dingo\Api\Routing\Router $api */
        $api->group(['prefix' => 'dictionary'], function ($api) {
            /* @var \Dingo\Api\Routing\Router $api */
            $namespace = 'App\Applications\Dictionary\Http\Controllers\\';

            $api->get('/country', ['as' => 'dictionary.country.list', 'uses' => $namespace.'DictionaryController@listCountries']);
            $api->get('/city', ['as' => 'dictionary.city.list', 'uses' => $namespace.'DictionaryController@listCities']);
            $api->get('/country/schema', ['as' => 'dictionary.country.schema', 'uses' => $namespace.'DictionaryController@countriesListSchema']);
        });
    });
});
