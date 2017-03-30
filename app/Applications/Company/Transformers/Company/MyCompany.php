<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 30/03/2017
 * Time: 14:28
 */

namespace App\Applications\Company\Transformers\Company;

use App\Domains\Employee\Entities\Employee;
use App\Core\Http\Requests\GetAPIRequest;
use App\Domains\Company\Entities\Company;
use League\Fractal\TransformerAbstract;
use App;

class MyCompany extends TransformerAbstract
{

    public function transform(Company $company)
    {
        return [

        ];
    }
}