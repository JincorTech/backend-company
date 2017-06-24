<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 21.06.17
 * Time: 13:39
 */

namespace App\Applications\Company\Http\Requests\Employee;

use App\Core\Http\Requests\BaseAPIRequest;
use App\Applications\Company\Http\Requests\AuthenticatedUser;
use App\Applications\Company\Http\Requests\Traits\PaginatedRequest;

class SearchContacts extends BaseAPIRequest
{
    use AuthenticatedUser, PaginatedRequest;

    public function rules() : array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
