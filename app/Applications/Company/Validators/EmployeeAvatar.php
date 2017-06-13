<?php
/**
 * Created by PhpStorm.
 * User: Artemii
 * Date: 09.06.2017
 * Time: 12:10
 */

namespace App\Applications\Company\Validators;

class EmployeeAvatar
{
    public function validate($attribute, $value, $params, $validator)
    {
        $explode = explode(',', $value);
        $format = str_replace([
                'data:image/',
                ';',
                'base64',
            ],
            '',
            $explode[0]
        );

        // check file format
        if ($format !== 'png') {
            return false;
        }

        // check base64 format
        if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
            return false;
        }

        return true;
    }

}