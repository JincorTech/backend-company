<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 05/05/2017
 * Time: 20:10
 */

namespace App\Applications\Dictionary\Http\Requests;

use App\Core\Http\Requests\GetAPIRequest;


class ListCitiesRequest extends GetAPIRequest
{

    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'country' => 'required|string|size:36'
        ];
    }

    /**
     * @return mixed
     */
    public function getCountryId()
    {
        return $this->get('country');
    }

}