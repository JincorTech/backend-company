<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 12/7/16
 * Time: 12:35 AM
 */

namespace App\Domains\Company\Entities;

use App\Core\ValueObjects\TranslatableString;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class CompanyType.
 *
 *
 * @ODM\Document(collection="companyTypes")
 */
class CompanyType
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     * @ODM\Id(strategy="NONE", type="bin_uuid")
     */
    protected $id;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $code;

    /**
     * @var TranslatableString
     *
     * @ODM\EmbedOne(
     *     targetDocument="App\Core\ValueObjects\TranslatableString"
     * )
     */
    protected $names;

    /**
     * CompanyType constructor.
     * @param array $names
     * @param string $code
     */
    public function __construct(array $names, string $code)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->setNames($names);
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $locale
     * @return mixed
     */
    public function getName($locale = null) : string
    {
        return $this->names->getValue($locale);
    }

    public function getNames()
    {
        return $this->names;
    }

    /**
     * @param array $names
     */
    private function setNames(array $names)
    {
        $this->names = new TranslatableString($names);
    }
}
