<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/14/16
 * Time: 7:16 PM
 */

namespace App\Applications\Dictionary\Http\Requests;

use App\Core\Http\Requests\GetAPIRequest;

class ListActivitiesRequest extends GetAPIRequest
{
    public function rules()
    {
        return [
            'name' => 'min:3',
            'locale' => 'string|size:2',
            'code' => 'string|min:2|max:6',
            'goodCodes' => 'string|min:2',
        ];
    }

    /**
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
     * @return string|null
     */
    public function getCode()
    {
        return $this->get('code', null);
    }

    /**
     * Returns array of passed goods codes.
     * @return array
     */
    public function getGoodCodes() : array
    {
        $codes = $this->get('goodCodes', null);
        if ($codes) {
            $codes = explode(',', $codes);
            foreach ($codes as $key => $code) {
                if (empty($code)) {
                    unset($codes[$key]);
                }
            }
        }

        return (array) $codes;
    }
}
