<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 12:59
 */

namespace App\Applications\Company\Http\Requests\MailingList;
use App\Core\Http\Requests\BaseAPIRequest;
use Illuminate\Validation\Rule;

class Subscribe extends BaseAPIRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email',
            'subject' => [
                'required',
                Rule::in(['ico', 'beta']),
            ],
        ];
    }
}
