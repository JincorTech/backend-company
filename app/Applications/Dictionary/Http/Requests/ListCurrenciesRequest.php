<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/14/16
 * Time: 6:55 PM
 */

namespace App\Applications\Dictionary\Http\Requests;

use App\Core\Http\Requests\GetAPIRequest;

class ListCurrenciesRequest extends GetAPIRequest
{
    public function rules()
    {
        return [
            'countryId' => 'string|size:36',
            'alpha3' => 'string|size:3',
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
     * @return string|null
     */
    public function getAlpha3()
    {
        return $this->get('alpha3', null);
    }
}
