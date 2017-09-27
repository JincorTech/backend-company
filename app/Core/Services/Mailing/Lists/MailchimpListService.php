<?php
/**
 * Created by PhpStorm.
 * User: alekns
 * Date: 21.09.17
 * Time: 20:18
 */

namespace App\Core\Services\Mailing\Lists;

use App\Core\Services\BaseRestService;
use App\Core\ValueObjects\ExtendedMailingListItem;
use App\Core\ValueObjects\MailingListItem;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MailchimpListService extends BaseRestService implements MailingListServiceInterface
{
    /**
     * MailchimpService constructor.
     */
    public function __construct()
    {
        $options = [
            'base_uri' => config('mailinglist.mailchimp.api.apiUri'),
            'auth' => [
                'api',
                config('mailinglist.mailchimp.api.secret'),
                'exceptions' => false
            ]
        ];
        parent::__construct($options);
    }

    /**
     * @param string $email
     * @return string
     */
    protected function getMailchimpMemberId(string $email)
    {
        return md5(mb_strtolower($email));
    }

    /**
     * @inheritdoc
     */
    public function addItemToList(MailingListItem $item)
    {
        $uri = "lists/{$item->getMailingListId()}/members";
        $this->client->post($uri, [
            'json' => [
                'email_address' => $item->getEmail(),
                'status' => 'subscribed',
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function deleteItemFromList(MailingListItem $item)
    {
        $uri = "lists/{$item->getMailingListId()}/members/{$this->getMailchimpMemberId($item->getEmail())}";
        $this->client->delete($uri);
    }

    /**
     * @return array
     */
    public function getMailingLists()
    {
        return config('mailinglist.mailchimp.lists');
    }

    public function addExtendedItemToList(ExtendedMailingListItem $item)
    {
        $uri = "lists/{$item->getMailingListId()}/members";

        $data = [
            'json' => [
                'email_address' => $item->getEmail(),
                'status' => 'subscribed',
                'merge_fields' => [
                    'MMERGE4' => $item->getLandingLanguage(),
                ],
                'language' => $item->getLandingLanguage(),
            ],
        ];

        if ($item->getCountry() !== null) {
            $data['json']['merge_fields']['MMERGE5'] = $item->getCountry();
        }

        $this->client->post($uri, $data);
    }
}
