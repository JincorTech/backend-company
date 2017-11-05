<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 05/10/2017
 * Time: 04:33
 */

namespace App\Domains\Employee\Events;
use DateTime;

/**
 * Class EmployeeActivated
 * @package App\Domains\Employee\Events
 *
 * Fired when employee is activated and can use web site
 */
class EmployeeActivated
{

    /**
     * @var string
     */
    public $login;

    /**
     * @var DateTime
     */
    public $activatedAt;

    /**
     * EmployeeActivated constructor.
     * @param string $login
     * @param DateTime $time
     */
    public function __construct(string $login, DateTime $time)
    {
        $this->login = $login;
        $this->activatedAt = $time;
    }
}
