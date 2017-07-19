<?php
use App\Core\Services\MailgunService;

class MailingListCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function testSubscribeIcoSuccess(ApiTester $I)
    {
        $I->wantTo('Add new email to ICO mailing list and receive success response');

        $mock = Mockery::mock(MailgunService::class);
        $mock->shouldReceive('addItemToList')->andReturnNull();
        $I->haveInstance(MailgunService::class, $mock);

        $I->sendPOST('mailingList/subscribe', [
            'email' => 'ortgma1@gmail.com',
            'subject' => 'ico',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'email' => 'ortgma1@gmail.com',
            'mailingListId' => 'ico@jincor.com',
        ]);
    }

    public function testSubscribeBetaSuccess(ApiTester $I)
    {
        $I->wantTo('Add new email to ICO mailing list and receive success response');

        $mock = Mockery::mock(MailgunService::class);
        $mock->shouldReceive('addItemToList')->andReturnNull();
        $I->haveInstance(MailgunService::class, $mock);

        $I->sendPOST('mailingList/subscribe', [
            'email' => 'ortgma1@gmail.com',
            'subject' => 'beta',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'email' => 'ortgma1@gmail.com',
            'mailingListId' => 'beta@jincor.com',
        ]);
    }

    public function testSubscribeAlreadyExists(ApiTester $I)
    {
        $I->wantTo('Add existing email to ICO mailing list and receive error');
        $I->sendPOST('mailingList/subscribe', [
            'email' => 'ortgma@gmail.com',
            'subject' => 'ico',
        ]);

        $errors = [
            'email' => [
                trans('exceptions.mailingList.item.already_exists'),
            ],
        ];

        $I->canSeeResponseContainsValidationErrors($errors);
    }

    public function testSubscribeInvalidEmail(ApiTester $I)
    {
        $I->wantTo('Try to add invalid email to ICO mailing list and receive validation error');

        $I->sendPOST('mailingList/subscribe', [
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

    public function testSubscribeNoEmail(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list without email and receive validation error');

        $I->sendPOST('mailingList/subscribe', [
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

    public function testSubscribeNoSubject(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list without subject and receive validation error');

        $I->sendPOST('mailingList/subscribe', [
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

    public function testSubscribeInvalidSubject(ApiTester $I)
    {
        $I->wantTo('Subscribe to ICO mailing list with invalid subject and receive validation error');

        $I->sendPOST('mailingList/subscribe', [
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

    public function testUnsubscribeSuccess(ApiTester $I)
    {
        $I->wantTo('Remove my email from ICO mailing list and receive success response');

        $mock = Mockery::mock(MailgunService::class);
        $mock->shouldReceive('deleteItemFromList')->andReturnNull();
        $I->haveInstance(MailgunService::class, $mock);

        $I->sendPOST('mailingList/unsubscribe', [
            'email' => 'ortgma@gmail.com',
            'subject' => 'ico',
        ]);

        $I->canSeeResponseCodeIs(200);
        $I->canSeeResponseContainsJson([
            'email' => 'ortgma@gmail.com',
            'mailingListId' => 'ico@jincor.com',
        ]);
    }

    public function testUnsubscribeNotExist(ApiTester $I)
    {
        $I->wantTo('Remove not existing email from ICO mailing list and receive error');

        $I->sendPOST('mailingList/unsubscribe', [
            'email' => 'ortgma123@gmail.com',
            'subject' => 'ico',
        ]);

        $I->canSeeResponseCodeIs(404);
        $I->canSeeResponseContainsJson([
            'message' => trans('exceptions.mailingList.item.not_found'),
        ]);
    }

    public function testUnsubscribeInvalidEmail(ApiTester $I)
    {
        $I->wantTo('Try to unsubscribe invalid email and receive validation error');

        $I->sendPOST('mailingList/unsubscribe', [
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

    public function testUnsubscribeNoEmail(ApiTester $I)
    {
        $I->wantTo('Unsubscribe without email and receive validation error');

        $I->sendPOST('mailingList/unsubscribe', [
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

    public function testUnsubscribeNoSubject(ApiTester $I)
    {
        $I->wantTo('Unsubscribe without subject and receive validation error');

        $I->sendPOST('mailingList/unsubscribe', [
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

    public function testUnsubscribeInvalidSubject(ApiTester $I)
    {
        $I->wantTo('Unsubscribe with invalid subject and receive validation error');

        $I->sendPOST('mailingList/unsubscribe', [
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
