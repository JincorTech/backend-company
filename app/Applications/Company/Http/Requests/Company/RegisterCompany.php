<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/19/17
 * Time: 4:23 PM
 */

namespace App\Applications\Company\Http\Requests\Company;

use App\Core\Http\Requests\BaseAPIRequest;

class RegisterCompany extends BaseAPIRequest
{
    public function rules()
    {
        return [
            'legalName' => 'required|string|min:3',
            'countryId' => 'required|string|size:36',
            'formattedAddress' => 'required|string',
            'companyType' => 'required|string|size:36',
        ];
    }

    /**
     * @return string|null
     */
    public function getLegalName()
    {
        return $this->get('legalName');
    }

    /**
     * @return string|null
     */
    public function getCountryId()
    {
        return $this->get('countryId');
    }

    /**
     * @return string|null
     */
    public function getFormattedAddress()
    {
        return $this->get('formattedAddress');
    }

    public function getCompanyTypeId() : string
    {
        return $this->get('companyType');
    }
}
