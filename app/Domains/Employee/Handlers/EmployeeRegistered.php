<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/30/17
 * Time: 8:09 PM
 */

namespace App\Domains\Employee\Handlers;

use App\Core\Interfaces\IdentityInterface;
use App\Domains\Employee\Events\EmployeeRegistered as ERE;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Employee\Mailables\RegistrationSuccess;
use App\Core\Interfaces\MessengerServiceInterface;
use Mail;
use App;

class EmployeeRegistered
{

    /**
     * @var IdentityInterface
     */
    protected $identityService;

    /**
     * @var MessengerServiceInterface
     */
    protected $messengerService;

    /**
     * EmployeeRegistered constructor.
     * @param IdentityInterface $identityService
     * @param MessengerServiceInterface $messengerService
     */
    public function __construct(IdentityInterface $identityService, MessengerServiceInterface $messengerService)
    {
        $this->dm = App::make(DocumentManager::class);
        $this->identityService = $identityService;
        $this->messengerService = $messengerService;

    }

    public function handle(ERE $event)
    {
        $data = $event->getData();
        $this->identityService->register($data);
        $this->notifyMessenger($data);
        if (env('APP_ENV') === 'testing') {
            return true;
        }
        Mail::to($data['email'])->queue(new RegistrationSuccess(
            $data['companyName'],
            $data['name'],
            $data['position']
        ));
    }

    public function notifyMessenger(array $eventData)
    {
        $data = [
            'username' => $eventData['tenant'] . '_' . str_replace('@', '_', $eventData['email']),
            'password' => $eventData['password'],
            "bind_email" => false,
        ];
        $this->messengerService->register($data, $eventData['employeeId']);
    }
}
