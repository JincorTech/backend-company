<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 13:04
 */

namespace App\Applications\Company\Services\MailingList;
use App\Core\Services\Mailing\Lists\MailingListServiceInterface;
use App\Core\ValueObjects\MailingListItem;
use App\Core\ValueObjects\ExtendedMailingListItem;
use App;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Core\Interfaces\MailingListRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Core\Exceptions\MailingListItemAlreadyExists;

class MailingListService
{
    /**
     * @var MailingListServiceInterface
     */
    protected $mailingService;

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
     * @param $mailingService MailingListServiceInterface
     * @param $mailingListRepository MailingListRepositoryInterface
     */
    public function __construct(MailingListServiceInterface $mailingService, MailingListRepositoryInterface $mailingListRepository)
    {
        $this->dm = App::make(DocumentManager::class);;
        $this->mailingService = $mailingService;
        $this->mailingListRepository = $mailingListRepository;
    }

    public function subscribe($email, $subject)
    {
        $mailingListId = $this->mailingService->getMailingLists()[$subject];
        $item = $this->mailingListRepository->findByEmailAndMailingListId($email, $mailingListId);
        if ($item) {
            throw new MailingListItemAlreadyExists([
                'email' => [
                    trans('exceptions.mailingList.item.already_exists'),
                ],
            ]);
        }

        $newItem = new MailingListItem($email, $mailingListId);
        /**
         * @var DocumentManager $dm
         */
        $this->dm->persist($newItem);
        $this->dm->flush();

        $this->mailingService->addItemToList($newItem);

        return $newItem;
    }

    public function subscribeExtended($email, $subject, array $data)
    {
        $mailingListId = $this->mailingService->getMailingLists()[$subject];
        $item = $this->mailingListRepository->findByEmailAndMailingListId($email, $mailingListId);
        if ($item) {
            throw new MailingListItemAlreadyExists([
                'email' => [
                    trans('exceptions.mailingList.item.already_exists'),
                ],
            ]);
        }

        $newItem = new ExtendedMailingListItem($email, $mailingListId, $data);
        /**
         * @var DocumentManager $dm
         */
        $this->dm->persist($newItem);
        $this->dm->flush();

        $this->mailingService->addExtendedItemToList($newItem);

        return $newItem;
    }

    public function unsubscribe($email, $subject)
    {
        $mailingListId = $this->mailingService->getMailingLists()[$subject];
        $item = $this->mailingListRepository->findByEmailAndMailingListId($email, $mailingListId);

        if (!$item) {
            throw new NotFoundHttpException(trans('exceptions.mailingList.item.not_found'));
        }

        $this->dm->remove($item);
        $this->dm->flush();
        $this->mailingService->deleteItemFromList($item);
        return $item;
    }
}
