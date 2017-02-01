<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/30/17
 * Time: 8:09 PM
 */

namespace App\Domains\Employee\Handlers;

use App\Core\Services\IdentityService;
use App\Domains\Employee\Events\EmployeeRegistered as ERE;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Employee\Mailables\RegistrationSuccess;
use Mail;
use App;

class EmployeeRegistered
{
    public function __construct()
    {
        $this->dm = App::make(DocumentManager::class);
        $this->identityService = new IdentityService();
    }

    public function handle(ERE $event)
    {
        $data = $event->getData();
        $this->identityService->register($data);
        if (env('APP_ENV') === 'testing') {
            return true;
        }
        Mail::to($data['email'])->queue(new RegistrationSuccess(
            $data['companyName'],
            $data['name'],
            $data['position']
        ));
    }
}
