<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 24/05/2017
 * Time: 11:15
 */

namespace App\Applications\Company\Http\Requests\Company;


use App\Applications\Company\Http\Requests\AuthenticatedUser;
use App\Core\Http\Requests\GetAPIRequest;

class Search extends GetAPIRequest
{
//    use AuthenticatedUser;

    /**
     * @return bool
     */
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
            'activity' => 'string|size:36',
            'country' => 'string|size:36',
            'request' => 'string|min:3',
        ];
    }


    public function getActivityId()
    {
        return $this->get('activity');
    }

    public function getCountryId()
    {
        return $this->get('country');
    }


    public function getQuery()
    {
        return $this->get('request');
    }
}