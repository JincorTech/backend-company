<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/24/17
 * Time: 9:24 PM
 */

namespace App\Applications\Company\Transformers;

use App\Domains\Company\Entities\EconomicalActivityType;
use League\Fractal\TransformerAbstract;

class EconomicalActivityTypeTransformer extends TransformerAbstract
{
    /**
     * User-defined locale.
     * @var string
     */
    private $locale;

    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    public function transform($type, $addChildren = true)
    {
        if ($type instanceof EconomicalActivityType) {
            $eaType = [
                'id' => $type->getId(),
                'name' => $type->getName($this->locale),
                'code' => $type->getCode(),
            ];
            $children = $type->getChildren()->getValues();
            /** @var EconomicalActivityType $child */
            foreach ($children as $key => $child) {
                $children[$key] = $this->transform($child, false);
                $eaType['children'] = $children;
            }

            return $eaType;
        } else {
            return $type;
        }
    }
}
