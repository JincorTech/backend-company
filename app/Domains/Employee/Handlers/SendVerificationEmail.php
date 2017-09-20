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
use App\Core\Services\JWTService;
use App;
use Mail;

class SendVerificationEmail
{
    /**
     * @param VerificationEmailRequested $event
     */
    public function handle(VerificationEmailRequested $event)
    {
    }
}
