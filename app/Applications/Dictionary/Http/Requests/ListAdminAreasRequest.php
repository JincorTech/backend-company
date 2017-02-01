<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/11/16
 * Time: 6:21 PM
 */

namespace App\Applications\Dictionary\Http\Requests;

use App\Core\Http\Requests\GetAPIRequest;

class ListAdminAreasRequest extends GetAPIRequest
{
    /**
     * Validation rules for list  admin areas request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'countryId' => 'string|size:36',
            'locale' => 'string|size:2',
            'alpha2' => 'string|size:2',
        ];
    }

    /**
     * @return string|null
     */
    public function getCountryId()
    {
        return $this->get('countryId', null);
    }

    /**
     * Return locale if present in request
     * Default app locale if doesn't.
     *
     * @return string
     */
    public function getLocale() : string
    {
        return parent::getLocale();
    }

    /**
     * @return string|null
     */
    public function getAlpha2()
    {
        return $this->get('alpha2', null);
    }
}
