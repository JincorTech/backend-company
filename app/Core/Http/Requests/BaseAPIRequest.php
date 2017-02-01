<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/7/16
 * Time: 7:20 PM
 */

namespace App\Core\Http\Requests;

use Dingo\Api\Http\FormRequest;

class BaseAPIRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
}
