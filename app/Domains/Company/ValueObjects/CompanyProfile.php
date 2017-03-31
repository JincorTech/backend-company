<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/29/16
 * Time: 10:52 PM
 */

namespace App\Domains\Company\ValueObjects;

use App\Core\ValueObjects\Address;
use App\Core\ValueObjects\TranslatableString;
use App\Domains\Company\Entities\CompanyType;
use App\Domains\Company\Entities\EconomicalActivityType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class CompanyProfile.
 *
 * @ODM\EmbeddedDocument
 */
class CompanyProfile
{

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $legalName;

    /**
     * @var TranslatableString
     *
     * @ODM\EmbedOne(
     *     targetDocument="App\Core\ValueObjects\TranslatableString"
     * )
     */
    protected $brandName;

    /**
     * @var CompanyType
     *
     * @ODM\ReferenceOne(
     *     targetDocument="App\Domains\Company\Entities\CompanyType",
     *     cascade={"persist"}
     * )
     */
    protected $companyType;


    /**
     * @var EconomicalActivityType
     *
     * @ODM\ReferenceMany(
     *     targetDocument="App\Domains\Company\Entities\EconomicalActivityType",
     *     cascade={"persist"}
     * )
     */
    protected $economicalActivities;

    /**
     * @var ArrayCollection
     *
     * @ODM\EmbedMany(
     *     targetDocument="App\Domains\Company\ValueObjects\CompanyExternalLink",
     * )
     */
    protected $links;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $phone;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $email;


    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $picture;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $description;


    /**
     * @var Address
     *
     * @ODM\EmbedOne(
     *     targetDocument="App\Core\ValueObjects\Address",
     * )
     */
    protected $address;



    public function __construct(string $name, Address $address, CompanyType $type)
    {
        $this->legalName = $name;
        $this->address  = $address;
        $this->companyType = $type;
        $this->economicalActivities = new ArrayCollection([]);
        $this->links = new ArrayCollection([]);
    }

    /**
     * @return ArrayCollection
     */
    public function getEconomicalActivities() : ArrayCollection
    {
        return $this->economicalActivities;
    }

    /**
     * @param EconomicalActivityType[] $activities
     */
    public function setEconomicalActivities(array $activities)
    {
        $this->economicalActivities = new ArrayCollection($activities);
    }


    /**
     * @return Address
     */
    public function getAddress() : Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function changeAddress(Address $address)
    {
        $this->address = $address;
    }


    /**
     * @param CompanyExternalLink[] $links
     */
    public function setLinks(array $links)
    {
        $this->links = new ArrayCollection($links);
    }


    /**
     *
     * @return ArrayCollection
     */
    public function getLinks() : ArrayCollection
    {
        return $this->links;
    }


    /**
     * @return CompanyType
     */
    public function getType() : CompanyType
    {
        return $this->companyType;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->legalName;
    }

    /**
     * @param array $names
     */
    public function setBrandNames(array $names)
    {
        $this->brandName = new TranslatableString($names);
    }

    /**
     * @param bool $all
     * @param null $locale
     * @return mixed|string
     */
    public function getBrandName($locale = null, bool $all = false)
    {
        if ($all) {
            return $this->brandName->getValues();
        }
        return $this->brandName->getValue($locale);
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPicture(): string
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     */
    public function setPicture(string $picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

}
