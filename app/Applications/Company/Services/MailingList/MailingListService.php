<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 13:04
 */

namespace App\Applications\Company\Services\MailingList;
use App\Core\ValueObjects\MailingListItem;
use App\Core\Services\MailgunService;
use App;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Core\Interfaces\MailingListRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Core\Exceptions\MailingListItemAlreadyExists;

class MailingListService
{
    /**
     * @var MailgunService
     */
    protected $mailgunService;

    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var MailingListRepositoryInterface
     */
    protected $mailingListRepository;

    /**
     * MailingListService constructor.
     *
     * @param $mailgunService MailgunService
     * @param $mailingListRepository MailingListRepositoryInterface
     */
    public function __construct(MailgunService $mailgunService, MailingListRepositoryInterface $mailingListRepository)
    {
        $this->dm = App::make(DocumentManager::class);;
        $this->mailgunService = $mailgunService;
        $this->mailingListRepository = $mailingListRepository;
    }

    public function subscribe($email, $subject)
    {
        $item = $this->mailingListRepository->findByEmailAndSubject($email, $subject);

        if ($item) {
            throw new MailingListItemAlreadyExists(trans('exceptions.mailingList.item.already_exists'));
        }

        $newItem = new MailingListItem($email, $subject);
        /**
         * @var DocumentManager $dm
         */
        $this->dm->persist($newItem);
        $this->dm->flush();

        $this->mailgunService->addItemToList($newItem);

        return $newItem;
    }

    public function unsubscribe($email, $subject)
    {
        $item = $this->mailingListRepository->findByEmailAndSubject($email, $subject);

        if (!$item) {
            throw new NotFoundHttpException(trans('exceptions.mailingList.item.not_found'));
        }

        $this->dm->remove($item);
        $this->dm->flush();
        $this->mailgunService->deleteItemFromList($item);
        return $item;
    }
}
