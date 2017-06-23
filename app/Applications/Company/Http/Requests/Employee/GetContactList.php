<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 21.06.17
 * Time: 12:43
 */

namespace App\Applications\Company\Http\Requests\Employee;
use App\Applications\Company\Http\Requests\AuthenticatedUser;
use App\Core\Http\Requests\BaseAPIRequest;
use App\Applications\Company\Http\Requests\Traits\PaginatedRequest;

class GetContactList extends BaseAPIRequest
{
    use AuthenticatedUser, PaginatedRequest;

    public function rules() : array
    {
        return [];
    }
}
