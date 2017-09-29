<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 26.09.17
 * Time: 16:43
 */

namespace App\Core\ValueObjects;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use DateTime;

/**
 * @ODM\Document
 */
class ExtendedMailingListItem extends MailingListItem
{
    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $name;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $company;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $position;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $browserLanguage;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $landingLanguage;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $ip;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $country;

    /**
     * @var DateTime
     * @ODM\Field(type="date")
     */
    protected $addedAt;

    /**
     * ExtendedMailingListItem constructor.
     * @param string $email
     * @param string $mailingListId
     * @param array $extraData
     */
    public function __construct($email, $mailingListId, array $extraData)
    {
        parent::__construct($email, $mailingListId);
        $this->name = $extraData['name'];
        $this->company = $extraData['company'];
        $this->position = $extraData['position'];
        $this->browserLanguage = $extraData['browserLanguage'];
        $this->landingLanguage = $extraData['landingLanguage'];
        $this->ip = $extraData['ip'];
        $this->country = $extraData['country'];
        $this->addedAt = new DateTime();
    }

    /**
     * @return string | null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string | null
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string | null
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @return string
     */
    public function getIp() : string
    {
        return $this->ip;
    }

    /**
     * @return string | null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getBrowserLanguage() : string
    {
        return $this->browserLanguage;
    }

    /**
     * @return string
     */
    public function getLandingLanguage() : string
    {
        return $this->landingLanguage;
    }

    /**
     * @return DateTime
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }
}
