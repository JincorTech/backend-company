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

    public function transform(Collection $colleaguesList)
    {
        $employeeTransformer = new Colleague();
        $invitedTransformer = new InvitedColleague();
        $activeArr = [];
        $deletedArr = [];
        $invitedArr = [];
        foreach ($colleaguesList->get('active') as $active) {
            array_push($activeArr, $employeeTransformer->transform($active));
        }
        foreach ($colleaguesList->get('deleted') as $deleted) {
            array_push($deletedArr, $employeeTransformer->transform($deleted));
        }
        foreach ($colleaguesList->get('invitations') as $invited) {
            array_push($invitedArr, $invitedTransformer->transform($invited));
        }

        return [
            'data' => [
                'self' => $employeeTransformer->transform($colleaguesList->get('self')),
                'active' => $activeArr,
                'deleted' => $deletedArr,
                'invited' => $invitedArr,
            ],
        ];

    }

}