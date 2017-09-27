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
use Ramsey\Uuid\Uuid;

/**
 * Class Company.
 *
 * @ODM\Document(
 *     collection="mailingList",
 *     repositoryClass="App\Core\Repositories\MailingListRepository"
 * )
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField(name="type")
 * @ODM\DiscriminatorMap({"standard" = "MailingListItem", "extended" = "ExtendedMailingListItem"})
 */
class MailingListItem
{
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

        $this->id = Uuid::uuid4()->toString();
        $this->email = $email;
        $this->mailingListId = $mailingListId;
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
