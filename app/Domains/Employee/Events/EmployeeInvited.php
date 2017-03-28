<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/21/17
 * Time: 11:25 PM
 */

namespace App\Domains\Employee\Events;


/**
 * Employee invite person to join
 *
 * Class EmployeeInvited
 * @package App\Domains\Company\Events
 */
class EmployeeInvited
{

    /**
     * @var string
     */
    private $employeeId;

    /**
     * @var string
     */
    private $companyId;

    /**
     * @var string
     */
    private $invitee;


    /**
     * EmployeeInvited constructor.
     * @param string $companyId
     * @param string $employeeId
     * @param string $invitee
     */
    public function __construct(string $companyId, string $employeeId, string $invitee)
    {
        $this->companyId = $companyId;
        $this->employeeId = $employeeId;
        $this->invitee = $invitee;
    }


    /**
     * @return string
     */
    public function getEmployeeId(): string
    {
        return $this->employeeId;
    }

    /**
     * @return string
     */
    public function getCompanyId(): string
    {
        return $this->companyId;
    }

    /**
     * @return string
     */
    public function getInvitee(): string
    {
        return $this->invitee;
    }


}