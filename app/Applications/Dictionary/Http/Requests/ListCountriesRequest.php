<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/7/16
 * Time: 7:04 PM
 */

namespace App\Applications\Dictionary\Http\Requests;

use App\Core\Http\Requests\GetAPIRequest;

/**
 * Class ListCountriesRequest.
 *
 * Describes list countries request including validation rules and
 * provides API over request params for more convient usage in app
 */
class ListCountriesRequest extends GetAPIRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Validation rules for list countries request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'name' => 'string|min:3',
            'locale' => 'string|size:2',
            'alpha2' => 'string|size:2',
        ];
    }

    /**
     * Get name parameter if present in request, null
     * if doesn't.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->get('name', null);
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
     * Get ISO2 code param from request.
     *
     * @return string|null
     */
    public function getAlpha2()
    {
        return $this->get('alpha2', null);
    }
}
