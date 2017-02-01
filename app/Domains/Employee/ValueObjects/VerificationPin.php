<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/17/17
 * Time: 7:02 PM
 */

namespace App\Domains\Employee\ValueObjects;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class VerificationPin.
 *
 * @ODM\EmbeddedDocument
 */
class VerificationPin
{
    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $code;

    public function __construct()
    {
        $this->code = rand(100000, 999999);
    }

    public function getCode() : string
    {
        return $this->code;
    }
}
