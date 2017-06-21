<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 20/04/2017
 * Time: 08:56
 */

namespace App\Domains\Employee\Handlers;

use App;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Core\Interfaces\IdentityInterface;
use App\Domains\Employee\Events\ScopeChanged;

class ScopeChangedHandler
{

    private $dm;

    private $identityService;

    /**
     * ScopeChangedHandler constructor.
     * @param IdentityInterface $identityService
     */
    public function __construct(IdentityInterface $identityService)
    {
        $this->dm = App::make(DocumentManager::class);
        $this->identityService = $identityService;
    }

    /**
     * @param ScopeChanged $event
     */
    public function handle(ScopeChanged $event)
    {
        $this->identityService->register($event->getData());
    }

}