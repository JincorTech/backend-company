<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 29/03/2017
 * Time: 17:32
 */

namespace App\Applications\Company\Transformers\Employee;


use App\Domains\Employee\Entities\Employee;
use App\Domains\Employee\Entities\EmployeeVerification;
use App\Domains\Employee\Entities\MetaEmployeeInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;

/**
 * Class Colleague
 * @package App\Applications\Company\Transformers\Employee
 *
 * @TODO: implement transformation
 */
class ColleagueList extends TransformerAbstract
{

    public function transform(MetaEmployeeInterface $employee)
    {
        if ($employee instanceof Employee) {
            return (new Colleague())->transform($employee);
        } elseif ($employee instanceof EmployeeVerification) {
            return (new InvitedColleague())->transform($employee);
        }

    }

}