<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 26.09.17
 * Time: 17:22
 */

namespace App\Applications\Company\Transformers\MailingList;
use App\Core\ValueObjects\ExtendedMailingListItem;

class ExtendedMailingListItemTransformer extends MailingListItemTransformer
{
    /**
     * @param ExtendedMailingListItem $item
     * @return array
     */
    public function transform($item)
    {
        $additionalFields = [
            'name' => $item->getName(),
            'company' => $item->getCompany(),
            'position' => $item->getPosition(),
            'ip' => $item->getIp(),
            'country' => $item->getCountry(),
            'browserLanguage' => $item->getBrowserLanguage(),
            'landingLanguage' => $item->getLandingLanguage(),
        ];
        return array_merge($additionalFields, parent::transform($item));
    }
}
