<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 16:24
 */

namespace App\Core\Services;
use App\Core\ValueObjects\MailingListItem;

class MailgunService extends BaseRestService
{
    public function __construct()
    {
        $options = [
            'base_uri' => config('services.mailgun.apiUri'),
            'auth' => [
                'api',
                config('services.mailgun.secret'),
            ],
        ];
        parent::__construct($options);
    }

    public function addItemToList(MailingListItem $item)
    {
        $uri = 'lists/' . $item->getMailingListId() . '/members';
        $this->client->post($uri, [
            'form_params' => [
                'address' => $item->getEmail(),
            ],
        ]);
    }

    public function deleteItemFromList(MailingListItem $item)
    {
        $uri = 'lists/' . $item->getMailingListId() . '/members/' . $item->getEmail();
        $this->client->delete($uri);
    }
}
