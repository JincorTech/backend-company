<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 26.09.17
 * Time: 17:19
 */

namespace App\Applications\Company\Http\Requests\MailingList;
use Illuminate\Validation\Rule;

class ExtendedSubscribe extends Subscribe
{
    // landing language must be accepted by mailchimp, see: https://kb.mailchimp.com/lists/manage-contacts/view-and-edit-subscriber-languages
    public function rules()
    {
        $additionalRules = [
            'name' => 'required|string|min:3',
            'company' => 'required|string|min:3',
            'position' => 'required|string|min:2',
            'browserLanguage' => 'required|string',
            'landingLanguage' => [
                'required',
                Rule::in([
                    'en',
                    'ar',
                    'af',
                    'be',
                    'bg',
                    'ca',
                    'zh',
                    'hr',
                    'cs',
                    'da',
                    'nl',
                    'et',
                    'fa',
                    'fi',
                    'fr',
                    'fr_CA',
                    'de',
                    'el',
                    'he',
                    'hi',
                    'hu',
                    'is',
                    'id',
                    'ga',
                    'it',
                    'ja',
                    'km',
                    'ko',
                    'lv',
                    'lt',
                    'mt',
                    'ms',
                    'mk',
                    'no',
                    'pl',
                    'pt',
                    'pt_PT',
                    'ro',
                    'ru',
                    'sr',
                    'sk',
                    'sl',
                    'es',
                    'es_ES',
                    'sw',
                    'sv',
                    'ta',
                    'th',
                    'tr',
                    'uk',
                    'vi',
                ]),
            ]
        ];

        return array_merge($additionalRules, parent::rules());
    }

    public function getExtendedData()
    {
        return [
            'name' => $this->get('name'),
            'company' => $this->get('company'),
            'position' => $this->get('position'),
            'browserLanguage' => $this->get('browserLanguage'),
            'landingLanguage' => $this->get('landingLanguage'),
            'ip' => $this->ip(),
            'country' => $this->country(),
        ];
    }

    public function ip()
    {
        return $_SERVER['HTTP_CF_CONNECTING_IP'] ?? parent::ip();
    }

    public function country()
    {
        return $_SERVER['HTTP_CF_IPCOUNTRY'] ?? null;
    }
}
