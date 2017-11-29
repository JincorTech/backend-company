<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 20.06.17
 * Time: 19:13
 */

namespace App\Applications\Company\Http\Requests\Employee;
use App\Applications\Company\Http\Requests\AuthenticatedUser;
use App\Core\Http\Requests\BaseAPIRequest;
use App\Applications\Company\Http\Requests\Traits\PaginatedRequest;

class QueryLogins extends BaseAPIRequest
{
    use AuthenticatedUser;

    public function rules() : array
    {
        return [
            'items' => 'required|array',
        ];
    }

    public function getLogins() : array
    {
        return $this->get('items');
    }
}
