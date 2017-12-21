<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 20.06.17
 * Time: 17:17
 */

namespace App\Domains\Employee\ValueObjects;
use App\Domains\Employee\Exceptions\EmployeeIsDeactivated;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Domains\Employee\Entities\Employee;

/**
 * Class ContactListItem
 * @package App\Domains\Employee\ValueObjects
 *
 * @ODM\EmbeddedDocument
 */
class EmployeeContactListItem
{
    /**
     * @var $employee
     * @ODM\ReferenceOne(
     *     targetDocument="App\Domains\Employee\Entities\Employee",
     *     cascade={"persist"}
     * )
     */
    protected $employee;

    /**
     * EmployeeContactListItem constructor.
     * @param Employee $employee
     * @throws EmployeeIsDeactivated
     */
    public function __construct(Employee $employee)
    {
        /*if (!$employee->isActive()) {
            throw new EmployeeIsDeactivated();
        }*/
        $this->employee = $employee;
    }

    /**
     * @return Employee
     */
    public function getEmployee() : Employee
    {
        return $this->employee;
    }
}
