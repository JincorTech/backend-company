<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/30/17
 * Time: 8:09 PM
 */

namespace App\Domains\Company\Handlers;

use App\Core\Services\IdentityService;
use App\Domains\Company\Entities\Employee;
use App\Domains\Company\Events\EmployeeRegisteredEvent;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Company\Mailables\RegistrationSuccess;
use Mail;
use App;

class EmployeeRegistered
{
    public function __construct()
    {
        $this->dm = App::make(DocumentManager::class);
        $this->identityService = new IdentityService();
        $this->employeeRepository = $this->dm->getRepository(Employee::class);
    }

    public function handle(EmployeeRegisteredEvent $event)
    {
        $data = $event->getData();
        $this->identityService->register($data);
        Mail::to($data['email'])->queue(new RegistrationSuccess(
            $data['companyName'],
            $data['name'],
            $data['position']
        ));
    }
}
