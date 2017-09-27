<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 15:15
 */

namespace App\Applications\Company\Transformers\MailingList;
use League\Fractal\TransformerAbstract;
use App\Core\ValueObjects\MailingListItem;

class MailingListItemTransformer extends TransformerAbstract
{
    /**
     * @param MailingListItem $item
     * @return array
     */
    public function transform($item)
    {
        return [
            'email' => $item->getEmail(),
            'mailingListId' => $item->getMailingListId(),
        ];
    }
}
