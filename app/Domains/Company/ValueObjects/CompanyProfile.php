<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/29/16
 * Time: 10:52 PM
 */

namespace App\Domains\Company\ValueObjects;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class CompanyProfile.
 *
 * @ODM\EmbeddedDocument
 */
class CompanyProfile
{
    /**
     * @var array
     * @ODM\Field(type="hash")
     */
    protected $brandName;

    /**
     * TODO: company types dictionary.
     * @var
     */
    protected $companyType;

    /**
     * @var string
     */
    protected $website;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $email;
}
