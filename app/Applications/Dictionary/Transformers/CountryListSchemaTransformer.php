<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/29/17
 * Time: 8:08 PM
 */

namespace App\Applications\Dictionary\Transformers;

use App\Core\Dictionary\Entities\Country;
use League\Fractal\TransformerAbstract;

class CountryListSchemaTransformer extends TransformerAbstract
{
    public function transform($param = null)
    {
        return [
            [
                'title' => 'Alpha2 Code',
                'name' => 'alpha2',
            ],
            [
                'title' => 'Name',
                'name' => 'name',
            ],
        ];
    }
}
