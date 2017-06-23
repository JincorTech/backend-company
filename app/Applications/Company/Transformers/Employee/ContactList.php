<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 20.06.17
 * Time: 23:40
 */

namespace App\Applications\Company\Transformers\Employee;
use League\Fractal\TransformerAbstract;
use App\Domains\Employee\ValueObjects\EmployeeContactListItem;

class ContactList extends TransformerAbstract
{
    public function transform(EmployeeContactListItem $item) : array
    {
        $employeeTransformer = new EmployeeContactList();
        return $employeeTransformer->transform($item->getEmployee());
    }
}
