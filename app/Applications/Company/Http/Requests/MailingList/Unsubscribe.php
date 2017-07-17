<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 13:01
 */

namespace App\Applications\Company\Http\Requests\MailingList;
use App\Core\Http\Requests\BaseAPIRequest;
use Illuminate\Validation\Rule;

class Unsubscribe extends BaseAPIRequest
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
