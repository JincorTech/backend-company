<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/22/17
 * Time: 11:08 AM
 */


return [

    'invitation' => [
        'alreadyExists' => 'Employee with e-mail: :email already exists in the company',
        'limitReached' => 'Cant send invitation to :email because your company reached the maximum limit of :limit invitations per one email'
    ],

    'restore-password' => [
        'notFound' => 'Employee with email :email were not found on JinCor'
    ],

    'change-password' => [
        'mismatch' => 'Old password don\'t match'
    ],

    'login' => [
        'multiple-companies' => 'User credentials matches to many companies. Please specify company id in order to login.'
    ],
    'employee' => [
        'not_found' => 'Employee :email not found',
        'password_mismatch' => 'Login and password do not match',
        'access_denied' => 'You don\'t have permissions permissions to perform this action',
    ],
    'company' => [
        'not_found' => 'Company not found on the server',
    ],
    'country' => [
        'not_found' => 'Country not found on the server',
    ],
    'city' => [
        'not_found' => 'City not found on the server',
    ],
    'economical_activity_type' => [
        'not_found' => 'Economical activity type not found on the server',
    ],
    'company_type' => [
        'not_found' => 'Company type not found on the server',
    ]

];