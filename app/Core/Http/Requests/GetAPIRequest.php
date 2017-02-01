<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/9/16
 * Time: 4:01 PM
 */

namespace App\Core\Http\Requests;

use App\Core\Exceptions\GetRequestValidationHttpException;
use Dingo\Api\Http\Request;
use Illuminate\Contracts\Validation\Validator;

class GetAPIRequest extends BaseAPIRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $request = $this->container['request'];
        if ($request instanceof Request) {
            if ($request->method() === 'GET') {
                throw new GetRequestValidationHttpException(null, $validator->errors());
            }
        }
        parent::failedValidation($validator);
    }

    public function getLocale() : string
    {
        return $this->get('locale', config('app.locale'));
    }
}
