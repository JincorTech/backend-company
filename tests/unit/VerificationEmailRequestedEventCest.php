<?php

use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Employee\Events\VerificationEmailRequested;
use App\Domains\Employee\Handlers\SendVerificationEmail;

class VerificationEmailRequestedEventCest
{

    /**
     * @var \App\Domains\Employee\Entities\EmployeeVerification
     */
    private $verification;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    private $dm;

    /**
     * VerificationEmailRequestedEventCest constructor.
     */
    public function __construct()
    {
        $this->dm = App::make(DocumentManager::class);
        $this->verification = EmployeeVerificationFactory::make();
    }

    /**
     * @param UnitTester $I
     */
    public function canCreateInstance(UnitTester $I)
    {
        $I->wantTo('Create new valid VerificationEmailRequested instance');
        $event = new VerificationEmailRequested($this->verification);
        $I->assertEquals($this->verification->getEmail(), $event->getEmail());
        $I->assertEquals($this->verification->getEmailCode(), $event->getCode());
    }


    /**
     * @param UnitTester $I
     */
    public function testHandler(UnitTester $I)
    {
        $I->wantTo('Test email handler');
        $event = new VerificationEmailRequested($this->verification);
        $I->assertInstanceOf(VerificationEmailRequested::class, $event);
        $handler = new SendVerificationEmail();
        $I->assertInstanceOf(SendVerificationEmail::class, $handler);
        $I->assertTrue($handler->handle($event));
    }

}
