<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 23/06/2017
 * Time: 02:28
 */

namespace App\Applications\Company\Http\Requests\Employee;


use App\Applications\Company\Http\Requests\AuthenticatedUser;
use App\Core\Http\Requests\BaseAPIRequest;

class ListByMatrixId extends BaseAPIRequest
{

    use AuthenticatedUser;

    /**
     * @return array
     * TODO: validate matrix IDS
     */
    public function rules() : array
    {
        return [
            'matrixIds' => 'array',
        ];
    }

    public function getMatrixIds() : array
    {
        return $this->get('matrixIds');
    }

}