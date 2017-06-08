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
use App\Core\Services\IdentityService;
use App\Domains\Employee\Events\ScopeChanged;

class ScopeChangedHandler
{

    private $dm;

    private $identityService;

    /**
     * ScopeChangedHandler constructor.
     */
    public function __construct()
    {
        $this->dm = App::make(DocumentManager::class);
        $this->identityService = new IdentityService();
    }

    /**
     * @param ScopeChanged $event
     */
    public function handle(ScopeChanged $event)
    {
        $this->identityService->register($event->getData());
    }

}