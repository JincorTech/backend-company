<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/27/17
 * Time: 2:39 AM
 */

namespace App\Domains\Employee\Handlers;


use App\Domains\Employee\Events\RestorePasswordRequested;
use App\Domains\Employee\Mailables\RestorePassword;
use Mail;

class SendRestorePasswordEmail
{

    public function handle(RestorePasswordRequested $event)
    {
        // @TODO: Remove when use RestAPI Service
        Mail::to($event->getEmail())->queue(new RestorePassword($event->getEmail(), $event->getId(), $event->getCode()));
    }
}