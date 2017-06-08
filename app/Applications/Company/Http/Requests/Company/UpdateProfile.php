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
        if ($this->getUser() instanceof Employee && $this->getUser()->isAdmin()) {
            return true;
        }
        return false;
    }

    public function rules()
    {
        return [
            'legalName' => 'string',
            'profile' => 'array',
            'profile.brandName' => 'array',
            'profile.description' => 'string|max:550',
            'profile.brandName.*' => 'string',
            'profile.links' => 'array',
            'profile.links.*' => 'url',
            'profile.address.country' => 'string|size:36',
            'profile.address.formattedAddress' => 'string',
            'profile.address.city' => 'string|size:36',
            'profile.email' => 'email',
            'profile.phone' => 'string',
            'profile.economicalActivityTypes' => 'array',
            'profile.economicalActivityTypes.*' => 'string|size:36',
            'profile.companyType' => 'string|size:36',
        ];
    }


    public function all() : array
    {
        $final = [];
        if ($this->get('profile')) {
            foreach ($this->get('profile') as $field => $value) {
                if (!empty($value)) {
                    $final[$field] = $value;
                }
            }
        }
        if (!empty($this->get('legalName'))) {
            $final['legalName'] = $this->get('legalName');
        }
        return $final;
    }
}