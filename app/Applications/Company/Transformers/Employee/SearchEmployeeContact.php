<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 23.06.17
 * Time: 23:21
 */

namespace App\Applications\Company\Transformers\Employee;


use App\Domains\Employee\Entities\Employee;
use Illuminate\Support\Facades\App;

class SearchEmployeeContact extends EmployeeContactList
{
    public function transform(Employee $employee): array
    {
        $result = parent::transform($employee);
        $result['added'] = App::make('AppUser')->isAddedToContactList($employee);
        return $result;
    }
}
