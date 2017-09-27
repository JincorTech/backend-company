<?php
/**
 * Created by PhpStorm.
 * User: alekns
 * Date: 21.09.17
 * Time: 20:20
 */

namespace App\Core\Services\Mailing\Lists;

use App\Core\ValueObjects\MailingListItem;
use App\Core\ValueObjects\ExtendedMailingListItem;

interface MailingListServiceInterface
{
    /**
     * @param MailingListItem $item
     * @return mixed
     */
    public function addItemToList(MailingListItem $item);

    /**
     * @param ExtendedMailingListItem $item
     * @return mixed
     */
    public function addExtendedItemToList(ExtendedMailingListItem $item);

    /**
     * @param MailingListItem $item
     * @return mixed
     */
    public function deleteItemFromList(MailingListItem $item);

    /**
     * @return array
     */
    public function getMailingLists();
}
