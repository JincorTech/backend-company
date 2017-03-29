<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/27/17
 * Time: 2:28 AM
 */

namespace App\Domains\Employee\Handlers;


use App\Domains\Employee\Events\VerificationEmailRequested;
use App\Domains\Employee\Mailables\VerifyEmail;
use Mail;

class SendVerificationEmail
{


    public function handle(VerificationEmailRequested $event)
    {
        Mail::to($event->getEmail())->queue(new VerifyEmail($event->getCode()));
    }

}