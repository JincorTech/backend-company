<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 12:27
 */

namespace App\Applications\Company\Http\Controllers;
use App\Applications\Company\Http\Requests\MailingList\Subscribe;
use App\Applications\Company\Http\Requests\MailingList\Unsubscribe;
use App\Applications\Company\Services\MailingList\MailingListService;
use App\Applications\Company\Transformers\MailingList\MailingListItemTransformer;

class MailingListController extends BaseController
{
    /**
     * @var MailingListService
     */
    protected $mailingListService;

    /**
     * MailingListController constructor.
     * @param $mailingService MailingListService
     */
    public function __construct(MailingListService $mailingService)
    {
        $this->mailingListService = $mailingService;
    }

    public function subscribe(Subscribe $request)
    {
        $item = $this->mailingListService->subscribe($request->get('email'), $request->get('subject'));
        return $this->response->item($item, MailingListItemTransformer::class);
    }

    public function unsubscribe(Unsubscribe $request)
    {
        $item = $this->mailingListService->unsubscribe($request->get('email'), $request->get('subject'));
        return $this->response->item($item, MailingListItemTransformer::class);
    }
}
