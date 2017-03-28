<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/21/17
 * Time: 11:25 PM
 */

namespace App\Domains\Company\Events;

use DateTime;

/**
 * Class CompanyAdded
 * @package App\Domains\Company\Events
 *
 * CompanyAdded event
 */
class CompanyAdded
{

    /**
     * @var string
     */
    private $companyId;

    /**
     * @var string
     */
    private $legalName;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * CompanyAdded constructor.
     * @param string $companyId
     * @param string $legalName
     */
    public function __construct(string $companyId, string $legalName)
    {
        $this->date = new DateTime();
        $this->companyId = $companyId;
        $this->legalName = $legalName;
    }

}