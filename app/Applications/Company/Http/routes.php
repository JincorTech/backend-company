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
            $api->get('/{id}', ['as' => 'company.info', 'uses' => $namespace.'CompanyController@info']);
            $api->post('/invite', ['as' => 'company.invite', 'uses' => $namespace.'CompanyController@invite']);

//            $api->put('/{id}', ['as' => 'company.update', 'uses' => $namespace.'CompanyController@update']);

            //Admin-only routes
            $api->get('/activityTypes/schema', ['as' => 'company.eatypes.schema', 'uses' => $namespace.'CompanyController@economicalActivityTypesSchema']);
        });

        $api->group(['prefix' => 'employee'], function ($api) {
            /* @var \Dingo\Api\Routing\Router $api */
            $namespace = 'App\Applications\Company\Http\Controllers\\';
            $api->post('/restorePassword', ['as' => 'employee.password.restore', 'uses' => $namespace.'EmployeeController@sendRestorePasswordEmail']);
            $api->post('/verifyEmail', ['as' => 'employee.email.verify', 'uses' => $namespace.'EmployeeController@verifyEmail']);
            $api->get('/verifyEmail', ['as' => 'employee.email.sendPin', 'uses' => $namespace.'EmployeeController@sendEmailCode']);

            $api->get('/me', ['as' => 'employee.me', 'uses' => $namespace . 'EmployeeController@me']);
            $api->put('/changePassword', ['as' => 'employee.email.password.change', 'uses' => $namespace.'EmployeeController@changePassword']);

//            $api->post('/verifyPhone', ['as' => 'employee.phone.verify', 'uses' => $namespace.'EmployeeController@verifyPhone']);
//            $api->get('/verifyPhone', ['as' => 'employee.phone.sendPin', 'uses' => $namespace.'EmployeeController@sendPhoneCode']);

            $api->post('/register', ['as' => 'employee.register', 'uses' => $namespace.'EmployeeController@register']);
            $api->post('/login', ['as' => 'employee.login', 'uses' => $namespace.'EmployeeController@login']);
            $api->get('/companies', ['uses' => $namespace.'EmployeeController@matchingCompanies']);
            $api->get('/{employeeId}', ['as' => 'employee.info', 'uses' => $namespace.'EmployeeController@info']);
        });
    });
});
