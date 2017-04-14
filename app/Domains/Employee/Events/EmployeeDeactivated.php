<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 14/04/2017
 * Time: 07:25
 */

namespace App\Domains\Employee\Events;

use App\Domains\Company\Entities\Company;
use DateTime;

class EmployeeDeactivated
{

    public $login;

    public $date;

    public $companyName;

    public function __construct(string $login, Company $company)
    {
        $this->login = $login;
        $this->date = new DateTime();
        $this->companyName = $company->getProfile()->getName();
    }

}