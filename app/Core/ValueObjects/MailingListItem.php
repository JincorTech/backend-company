<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 12:06
 */

namespace App\Core\ValueObjects;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use InvalidArgumentException;
use App\Core\Exceptions\UnknownMailingListId;
use Ramsey\Uuid\Uuid;

/**
 * Class Company.
 *
 * @ODM\Document(
 *     collection="mailingList",
 *     repositoryClass="App\Core\Repositories\MailingListRepository"
 * )
 */
class MailingListItem
{
    const MAILING_LIST_IDS = [
        'ico' => 'ico@jincor.com',
        'beta' => 'beta@jincor.com',
    ];

    /**
     * @var string
     * @ODM\Id(strategy="NONE", type="bin_uuid")
     */
    protected $id;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $email;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $mailingListId;

    public function __construct(string $email, string $mailingListId)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException(trans('exceptions.email.invalid'));
        }

        if (!array_key_exists($mailingListId, static::MAILING_LIST_IDS)) {
            throw new UnknownMailingListId(trans('exceptions.mailingList.id.unknown'));
        }

        $this->id = Uuid::uuid4()->toString();
        $this->email = $email;
        $this->mailingListId = static::MAILING_LIST_IDS[$mailingListId];
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getMailingListId()
    {
        return $this->mailingListId;
    }
}
