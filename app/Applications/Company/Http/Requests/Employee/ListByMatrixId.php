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
     */
    public function rules() : array
    {
        return [
            'matrixIds' => 'required|array',
            //validate that id starts with @, then contains 36 chars ID and then it has email with @ replaced to _
            //email validation regex is too complicated so I suppose it's enough to leave it like this.
            'matrixIds.*' => 'string|regex:/^@[a-z\d\-]{36}_.+_.+$/',
        ];
    }

    public function getMatrixIds() : array
    {
        return $this->get('matrixIds');
    }

}