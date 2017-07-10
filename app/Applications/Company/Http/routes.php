<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/18/16
 * Time: 3:45 PM
 *
 * @SWG\Swagger(
 *     basePath="/api/v1",
 *     host="",
 *     schemes={"http", "https"},
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Universal Business Network API",
 *         @SWG\Contact(name="Andrey Degtyaruk", email="hlogeon1@gmail.com")
 *     ),
 *     @SWG\Definition(
 *         definition="Error",
 *         required={"status_code", "message"},
 *         @SWG\Property(
 *             property="status_code",
 *             type="integer",
 *             format="int32"
 *         ),
 *         @SWG\Property(
 *             property="message",
 *             type="string"
 *         )
 *     )
 * )
 */

/** @var \Dingo\Api\Routing\Router $api */
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    /* @var \Dingo\Api\Routing\Router $api */
    $api->group(['prefix' => 'v1'], function ($api) {

        /* @var \Dingo\Api\Routing\Router $api */
        $api->group(['prefix' => 'company'], function ($api) {
            /* @var \Dingo\Api\Routing\Router $api */
            $namespace = 'App\Applications\Company\Http\Controllers\\';

            $api->post('/', ['as' => 'company.register', 'uses' => $namespace.'CompanyController@register']);
            $api->get('/types', ['as' => 'company.types', 'uses' => $namespace.'CompanyController@companyTypes']);
            $api->get('/activityTypes', ['as' => 'company.eatypes', 'uses' => $namespace.'CompanyController@economicalActivityTypes']);
            $api->get('/my', ['as' => 'company.my', 'uses' => $namespace . 'CompanyController@my']);
            $api->put('/my', ['as' => 'company.update', 'uses' => $namespace . 'CompanyController@update']);
            $api->get('/search', ['as' => 'company.search', 'uses' => $namespace. 'CompanyController@search']);
            $api->get('/{id}', ['as' => 'company.info', 'uses' => $namespace.'CompanyController@info']);
            $api->post('/invite', ['as' => 'company.invite', 'uses' => $namespace.'CompanyController@invite']);

//            $api->put('/{id}', ['as' => 'company.update', 'uses' => $namespace.'CompanyController@update']);

        });

        $api->group(['prefix' => 'employee'], function ($api) {
            /* @var \Dingo\Api\Routing\Router $api */
            $namespace = 'App\Applications\Company\Http\Controllers\\';
            $api->post('/restorePassword', ['as' => 'employee.password.restore', 'uses' => $namespace.'EmployeeController@sendRestorePasswordEmail']);
            $api->post('/verifyEmail', ['as' => 'employee.email.verify', 'uses' => $namespace.'EmployeeController@verifyEmail']);
            $api->get('/verifyEmail', ['as' => 'employee.email.sendPin', 'uses' => $namespace.'EmployeeController@sendEmailCode']);

            $api->delete('/{id}', ['as' => 'employee.delete', 'uses' => $namespace.'EmployeeController@delete']);

            $api->get('/me', ['as' => 'employee.me', 'uses' => $namespace . 'EmployeeController@me']);
            $api->put('/me', ['as' => 'employee.update', 'uses' => $namespace . 'EmployeeController@update']);
            $api->get('/colleagues', ['as' => 'employee.colleagues', 'uses' => $namespace . 'EmployeeController@colleagues']);
            $api->put('/changePassword', ['as' => 'employee.email.password.change', 'uses' => $namespace.'EmployeeController@changePassword']);
            $api->put('/admin', ['as' => 'employee.admin', 'uses' => $namespace.'EmployeeController@makeAdmin']);

//            $api->post('/verifyPhone', ['as' => 'employee.phone.verify', 'uses' => $namespace.'EmployeeController@verifyPhone']);
//            $api->get('/verifyPhone', ['as' => 'employee.phone.sendPin', 'uses' => $namespace.'EmployeeController@sendPhoneCode']);

            $api->post('/register', ['as' => 'employee.register', 'uses' => $namespace.'EmployeeController@register']);
            $api->post('/login', ['as' => 'employee.login', 'uses' => $namespace.'EmployeeController@login']);
            $api->get('/companies', ['uses' => $namespace.'EmployeeController@matchingCompanies']);
            $api->get('/contacts/search', ['as' => 'employee.contacts.search', 'uses' => $namespace.'EmployeeController@searchContacts']);
            $api->get('/contacts', ['as' => 'employee.contacts.getList', 'uses' => $namespace.'EmployeeController@getContactList']);
            $api->post('/contacts', ['as' => 'employee.contacts.add', 'uses' => $namespace.'EmployeeController@addContact']);
            $api->post('/matrix', ['as' => 'employee.matrix', 'uses' => $namespace.'EmployeeController@matrix']); //use POST because of GET query length limitations
            $api->delete('/contacts/{id}', ['as' => 'employee.contacts.delete', 'uses' => $namespace.'EmployeeController@deleteContact']);
        });
    });
});
