<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 14/04/2017
 * Time: 05:37
 */

namespace App\Applications\Company\Http\Requests\Employee;


use App\Applications\Company\Http\Requests\AuthenticatedUser;
use App\Core\Http\Requests\BaseAPIRequest;
use Illuminate\Support\Collection;

class UpdateRequest extends BaseAPIRequest
{
    use AuthenticatedUser;

    public function rules()
    {
        return [
            'profile.firstName' => 'string|min:2|max:64',
            'profile.lastName' => 'string|min:2|max:64',
            'profile.position' => 'string|min:4|max:256',
            'profile.avatar' => 'is_png',
        ];
    }

    public function getFirstName()
    {
        return $this->get('profile.firstName');
    }

    public function getLastName()
    {
        return $this->get('profile.lastName');
    }

    public function getPosition()
    {
        return $this->get('profile.position');
    }

    public function getAvatar()
    {
        return $this->get('profile.avatar');
    }

    public function all()
    {
        return Collection::make($this->get('profile'))->filter(function($item) {
            return !empty($item);
        })->values();
    }

}