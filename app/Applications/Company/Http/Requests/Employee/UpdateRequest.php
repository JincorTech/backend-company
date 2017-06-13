<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 14/04/2017
 * Time: 05:37
 */

namespace App\Applications\Company\Http\Requests\Employee;


use App\Applications\Company\Http\Requests\AuthenticatedUser;
use App\Applications\Company\Validators\EmployeeAvatar;
use App\Core\Http\Requests\BaseAPIRequest;
use Illuminate\Support\Collection;

class UpdateRequest extends BaseAPIRequest
{
    use AuthenticatedUser;

    public function rules()
    {
        return [
            'profile.firstName' => 'filled|string|min:2|max:64',
            'profile.lastName' => 'filled|string|min:2|max:64',
            'profile.position' => 'filled|string|min:2|max:60',
            'profile.avatar' => 'is_png',
        ];
    }

    public function getFirstName() : string
    {
        return $this->get('profile.firstName');
    }

    public function getLastName() : string
    {
        return $this->get('profile.lastName');
    }

    public function getPosition() : string
    {
        return $this->get('profile.position');
    }

    public function getAvatar()
    {
        return $this->get('profile.avatar');
    }
}