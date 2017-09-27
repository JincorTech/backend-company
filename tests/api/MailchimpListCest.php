<?php

use App\Core\Services\Mailing\Lists\MailchimpListService;
use App\Core\Services\Mailing\Lists\MailingListServiceInterface;

require(__DIR__ . '/MailgunMailingListCest.php');

class MailchimpListCest extends MailgunMailingListCest
{
    protected static $serviceClass = MailchimpListService::class;

    public function _before(ApiTester $I)
    {
        putenv('MAILING_LIST_DRIVER=mailchimp');
    }

    public function testSubscribeV2IcoSuccess(ApiTester $I)
    {
        $I->wantTo('Add new email to ICO mailing list and receive success response');

        $mock = Mockery::mock(MailchimpListService::class);
        $mock->shouldReceive('addExtendedItemToList')->andReturnNull();
        $I->haveInstance(MailingListServiceInterface::class, $mock->makePartial());

        $_SERVER['HTTP_CF_CONNECTING_IP'] = '1.2.3.4';
        $_SERVER['HTTP_CF_IPCOUNTRY'] = 'RU';

        $I->sendPOST('mailingList/subscribev2', [
            'email' => 'arti123@arti.com',
            'subject' => 'ico',
            'name' => 'John Doe',
            'company' => 'Jincor',
            'position' => 'CEO',
            'browserLanguage' => 'ru',
            'landingLanguage' => 'ru',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'email' => 'arti123@arti.com',
            'mailingListId' => $mock->getMailingLists()['ico'],
            'name' => 'John Doe',
            'company' => 'Jincor',
            'position' => 'CEO',
            'ip' => '1.2.3.4',
            'country' => 'RU',
            'browserLanguage' => 'ru',
            'landingLanguage' => 'ru',
        ]);
    }

    public function testSubscribeV2BetaSuccess(ApiTester $I)
    {
        $I->wantTo('Add new email to ICO mailing list and receive success response');

        $mock = Mockery::mock(MailchimpListService::class);
        $mock->shouldReceive('addExtendedItemToList')->andReturnNull();
        $I->haveInstance(MailingListServiceInterface::class, $mock->makePartial());

        $_SERVER['HTTP_CF_CONNECTING_IP'] = '1.2.3.4';
        $_SERVER['HTTP_CF_IPCOUNTRY'] = 'US';

        $I->sendPOST('mailingList/subscribev2', [
            'email' => 'ortgma1@gmail.com',
            'subject' => 'beta',
            'name' => 'John Doe',
            'company' => 'Jincor',
            'position' => 'CEO',
            'browserLanguage' => 'en',
            'landingLanguage' => 'ru',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'email' => 'ortgma1@gmail.com',
            'mailingListId' => $mock->getMailingLists()['beta'],
            'name' => 'John Doe',
            'company' => 'Jincor',
            'position' => 'CEO',
            'ip' => '1.2.3.4',
            'country' => 'US',
            'browserLanguage' => 'en',
            'landingLanguage' => 'ru',
        ]);
    }

    public function testSubscribeV2AlreadyExists(ApiTester $I)
    {
        $I->wantTo('Add existing email to ICO mailing list and receive error');
        $I->sendPOST('mailingList/subscribev2', [
            'email' => 'ortgma@gmail.com',
            'subject' => 'ico',
            'name' => 'John Doe',
            'company' => 'Jincor',
            'position' => 'CEO',
            'browserLanguage' => 'en',
            'landingLanguage' => 'en',
        ]);

        $errors = [
            'email' => [
                trans('exceptions.mailingList.item.already_exists'),
            ],
        ];

        $I->canSeeResponseContainsValidationErrors($errors);
    }

    public function testSubscribeV2AlreadyExistsExtended(ApiTester $I)
    {
        $I->wantTo('Add existing email (extended) to ICO mailing list and receive error');
        $I->sendPOST('mailingList/subscribev2', [
            'email' => 'extended.exist@jincor.com',
            'subject' => 'ico',
            'name' => 'John Doe',
            'company' => 'Jincor',
            'position' => 'CEO',
            'browserLanguage' => 'en',
            'landingLanguage' => 'en',
        ]);

        $errors = [
            'email' => [
                trans('exceptions.mailingList.item.already_exists'),
            ],
        ];

        $I->canSeeResponseContainsValidationErrors($errors);
    }

    public function testSubscribeV2InvalidEmail(ApiTester $I)
    {
        $I->wantTo('Try to add invalid email to ICO mailing list and receive validation error');

        $I->sendPOST('mailingList/subscribev2', [
            'email' => 'ortgma.gmail.com',
            'subject' => 'ico',
        ]);

        $message = trans('validation.email', [
            'attribute' => 'email',
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'email' => [
                $message,
            ],
        ]);
    }

    public function testSubscribeV2NoEmail(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list without email and receive validation error');

        $I->sendPOST('mailingList/subscribev2', [
            'subject' => 'ico',
        ]);

        $message = trans('validation.required', [
            'attribute' => 'email',
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'email' => [
                $message,
            ],
        ]);
    }

    public function testSubscribeV2NoName(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list without name and receive validation error');

        $I->sendPOST('mailingList/subscribev2', [
            'subject' => 'ico',
        ]);

        $message = trans('validation.required', [
            'attribute' => 'name',
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'name' => [
                $message,
            ],
        ]);
    }

    public function testSubscribeV2TooShortName(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list with too short name and receive validation error');

        $I->sendPOST('mailingList/subscribev2', [
            'subject' => 'ico',
            'name' => 'Na'
        ]);

        $message = trans('validation.min.string', [
            'attribute' => 'name',
            'min' => 3,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'name' => [
                $message,
            ],
        ]);
    }

    public function testSubscribeV2TooShortCompany(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list with too short company and receive validation error');

        $I->sendPOST('mailingList/subscribev2', [
            'subject' => 'ico',
            'company' => 'Na'
        ]);

        $message = trans('validation.min.string', [
            'attribute' => 'company',
            'min' => 3,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'company' => [
                $message,
            ],
        ]);
    }

    public function testSubscribeV2TooShortPosition(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list with too short position and receive validation error');

        $I->sendPOST('mailingList/subscribev2', [
            'subject' => 'ico',
            'position' => 'N'
        ]);

        $message = trans('validation.min.string', [
            'attribute' => 'position',
            'min' => 2,
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'position' => [
                $message,
            ],
        ]);
    }

    public function testSubscribeV2NoCompany(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list without company and receive validation error');

        $I->sendPOST('mailingList/subscribev2', [
            'subject' => 'ico',
        ]);

        $message = trans('validation.required', [
            'attribute' => 'company',
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'company' => [
                $message,
            ],
        ]);
    }

    public function testSubscribeV2NoPosition(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list without position and receive validation error');

        $I->sendPOST('mailingList/subscribev2', [
            'subject' => 'ico',
        ]);

        $message = trans('validation.required', [
            'attribute' => 'position',
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'position' => [
                $message,
            ],
        ]);
    }

    public function testSubscribeV2NoBrowserLanguage(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list without browser language and receive validation error');

        $I->sendPOST('mailingList/subscribev2', [
            'subject' => 'ico',
        ]);

        $message = trans('validation.required', [
            'attribute' => 'browser language',
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'browserLanguage' => [
                $message,
            ],
        ]);
    }

    public function testSubscribeV2NoLandingLanguage(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list without landing language and receive validation error');

        $I->sendPOST('mailingList/subscribev2', [
            'subject' => 'ico',
        ]);

        $message = trans('validation.required', [
            'attribute' => 'landing language',
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'landingLanguage' => [
                $message,
            ],
        ]);
    }

    public function testSubscribeV2InvalidLandingLanguage(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list with invalid landing language and receive validation error');

        $I->sendPOST('mailingList/subscribev2', [
            'subject' => 'ico',
            'landingLanguage' => 'SOME'
        ]);

        $message = trans('validation.in', [
            'attribute' => 'landing language',
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'landingLanguage' => [
                $message,
            ],
        ]);
    }

    public function testSubscribeV2NoSubject(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list without subject and receive validation error');

        $I->sendPOST('mailingList/subscribev2', [
            'email' => 'test@test.com',
        ]);

        $message = trans('validation.required', [
            'attribute' => 'subject',
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'subject' => [
                $message,
            ],
        ]);
    }

    public function testSubscribeV2InvalidSubject(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list with invalid subject and receive validation error');

        $I->sendPOST('mailingList/subscribev2', [
            'email' => 'test@test.com',
            'subject' => 'random',
        ]);

        $message = trans('validation.in', [
            'attribute' => 'subject',
        ]);

        $I->canSeeResponseContainsValidationErrors([
            'subject' => [
                $message,
            ],
        ]);
    }
}
