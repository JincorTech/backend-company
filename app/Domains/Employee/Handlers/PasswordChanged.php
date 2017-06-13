<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/22/17
 * Time: 2:35 PM
 */

namespace App\Domains\Employee\Handlers;

use Doctrine\ODM\MongoDB\DocumentManager;
use App\Core\Interfaces\IdentityInterface;
use App\Domains\Employee\Events\PasswordChanged as PCE;
use App;

class PasswordChanged
{

    /**
     * PasswordChanged constructor.
     * @param IdentityInterface $identityService
     */
    public function __construct(IdentityInterface $identityService)
    {
        $this->dm = App::make(DocumentManager::class);
        $this->identityService = $identityService;
    }


    public function handle(PCE $passwordChanged)
    {
        $this->identityService->register($passwordChanged->getData());
    }

}