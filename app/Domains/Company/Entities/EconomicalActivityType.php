<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/22/16
 * Time: 12:17 AM
 */

namespace App\Domains\Company\Entities;

use App\Core\ValueObjects\TranslatableString;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class EconomicalActivityType.
 *
 * @ODM\Document(
 *     collection="economicalActivityTypes",
 *     repositoryClass="App\Domains\Company\Repositories\EconomicalActivityRepository"
 * )
 * @Gedmo\Tree(type="materializedPath", activateLocking=true)
 */
class EconomicalActivityType
{
    /**
     * @var string
     *
     * @ODM\Id(strategy="NONE", type="bin_uuid")
     */
    protected $id;

    /**
     * @var TranslatableString
     *
     * @ODM\EmbedOne(
     *     targetDocument="App\Core\ValueObjects\TranslatableString"
     * )
     */
    protected $names;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     * @Gedmo\TreePathSource
     */
    protected $internalCode;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     * @Gedmo\TreePath(separator=".")
     */
    protected $path;

    /**
     * @var EconomicalActivityType|null
     *
     * @ODM\ReferenceOne(targetDocument="App\Domains\Company\Entities\EconomicalActivityType")
     * @Gedmo\TreeParent
     */
    protected $parent;

    /**
     * @var int
     *
     * @Gedmo\TreeLevel
     * @ODM\Field(type="int")
     */
    protected $level;

    /**
     * @var \DateTime
     *
     * @Gedmo\TreeLockTime
     * @ODM\Field(type="date")
     */
    protected $lockTime;

    /**
     * @ODM\ReferenceMany(targetDocument="App\Domains\Company\Entities\EconomicalActivityType", mappedBy="parent")
     */
    public $children;

    public function __construct(array $names, $internalCode)
    {
        $this->id = Uuid::uuid4();
        $this->setNames($names);
        $this->internalCode = $internalCode;
        $this->children = new ArrayCollection();
    }

    /**
     * @return UuidInterface|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $locale
     * @return mixed
     */
    public function getName(string $locale = null) : string
    {
        return $this->names->getValue($locale);
    }

    /**
     * @param array $names
     */
    public function setNames(array $names)
    {
        $this->names = new TranslatableString($names);
    }

    /**
     * @param EconomicalActivityType|null $parent
     */
    public function setParent(EconomicalActivityType $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * @return EconomicalActivityType|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->internalCode;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren() : ArrayCollection
    {
        return $this->children;
    }
}
