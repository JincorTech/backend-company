<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/11/16
 * Time: 7:47 PM
 */

namespace App\Applications\Dictionary\Http\Requests;

use App\Core\Http\Requests\GetAPIRequest;

class ListPhoneCodesRequest extends GetAPIRequest
{
    /**
     * Validation rules for list phone codes request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'countryId' => 'string|size:36',
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
     * @return string|null
     */
    public function getAlpha2()
    {
        return $this->get('alpha2', null);
    }
}
