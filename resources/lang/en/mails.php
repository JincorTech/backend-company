<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/30/17
 * Time: 9:48 PM
 */

return [

    'verification' => [
        'greeting' => 'Hello! Your verification PIN is :code',
        'intro' => 'Someone is trying to register at Jincor with this email. 
        If its you just enter this PIN code on the registration page or simply click the button from this email.',
        'verifyButton' => 'Verify',
        'outro' => 'If its not you just ignore this email',
        'bye' => 'Best Regards, Jincor team',
    ],

    'invitation' => [
        'greeting' => 'Hello! One of your colleagues (:employee) is asking you to join Jincor',
        'intro' => 'Your verification PIN is :code. To accept invitation and register in just few moments press the "Accept invite!" button and follow instructions',
        'registerButton' => 'Accept Invite',
        'outro' => 'If you don\'t like to join our platform just ignore this e-mail',
        'bye' => 'Best Regards, Jincor team',
    ],


    'restore' => [
        'greeting' => 'Someone asked to restore your password! Your PIN code is :code',
        'intro' => 'To verify that this email address belongs to you please enter PIN code at Jincor or just press the button',
        'verifyButton' => 'Restore password',
        'outro' => 'If its were not you, don\'t worry and just ignore this email. Only ones who has access to your email can change the password',
        'bye' => 'Best Regards, Jincor team',
    ],


    'registration_complete' => [
        'greeting' => 'Greetings, :employee_name!',
        'intro' => 'You just finished registration process at Jincor as :employee_position in :company_name',
        'outro' => 'Now you can log into your account using credentials you used for registration',
        'bye' => 'Best Regards, Jincor team',
    ]
];