<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 16:24
 */

namespace App\Core\Services\Mailing\Lists;

use App\Core\Services\BaseRestService;
use App\Core\ValueObjects\MailingListItem;

class MailgunListService extends BaseRestService implements MailingListServiceInterface
{
    public function __construct()
    {
        $options = [
            'base_uri' => config('mailinglist.mailgun.api.apiUri'),
            'auth' => [
                'api',
                config('mailinglist.mailgun.api.secret'),
            ],
        ];
        parent::__construct($options);
    }

    /**
     * @inheritdoc
     */
    public function addItemToList(MailingListItem $item)
    {
        $uri = 'lists/' . $item->getMailingListId() . '/members';
        $this->client->post($uri, [
            'form_params' => [
                'address' => $item->getEmail(),
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function deleteItemFromList(MailingListItem $item)
    {
        $uri = 'lists/' . $item->getMailingListId() . '/members/' . $item->getEmail();
        $this->client->delete($uri);
    }

    /**
     * @return array
     */
    public function getMailingLists()
    {
        return config('mailinglist.mailgun.lists');
    }
}
