<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 02/05/2017
 * Time: 18:59
 */

namespace App\Domains\Company\Events;


use App\Domains\Company\Entities\Company;
use DateTime;

class BaseCompanyEvent
{

    /**
     * @var Company
     */
    private $company;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * CompanyAdded constructor.
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->date = new DateTime();
        $this->company = $company;
    }

    /**
     * @return Company
     */
    public function getCompany() : Company
    {
        return $this->company;
    }
}