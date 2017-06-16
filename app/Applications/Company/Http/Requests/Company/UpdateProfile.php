<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 14/04/2017
 * Time: 09:18
 */

namespace App\Applications\Company\Http\Requests\Company;


use App\Applications\Company\Http\Requests\AuthenticatedUser;
use App\Core\Http\Requests\BaseAPIRequest;
use App\Domains\Employee\Entities\Employee;

class UpdateProfile extends BaseAPIRequest
{

    use AuthenticatedUser;

    public function authorize()
    {
        return $this->getUser() instanceof Employee && $this->getUser()->isAdmin();
    }

    public function rules()
    {
        return [
            'legalName' => 'filled|string|min:3',
            'profile' => 'array',
            'profile.brandName' => 'array',
            'profile.description' => 'string|max:550',
            'profile.brandName.*' => 'string',
            'profile.links' => 'array',
            'profile.links.*.name' => 'string',
            'profile.links.*.value' => 'url',
            'profile.address.country' => 'string|size:36',
            'profile.address.formattedAddress' => 'nullable|string',
            'profile.address.city' => 'string|size:36',
            'profile.email' => 'nullable|email',
            'profile.phone' => 'nullable|string',
            'profile.economicalActivityTypes' => 'array',
            'profile.economicalActivityTypes.*' => 'string|size:36',
            'profile.companyType' => 'string|size:36',
            'profile.picture' => 'nullable|is_png'
        ];
    }
}