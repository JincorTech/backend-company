<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/10/16
 * Time: 5:06 PM
 */

namespace App\Applications\Dictionary\CriteriaBuilders;

use App\Applications\Dictionary\Http\Requests\ListCountriesRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Core\CriteriaBuilders\CriteriaBuilderInterface;
use Illuminate\Http\Request;

class CountryCriteriaBuilder implements CriteriaBuilderInterface
{
    public static function fromRequest(Request $request)
    {
        if ($request instanceof ListCountriesRequest) {
            return static::listCountriesRequest($request);
        }
        throw new \InvalidArgumentException('Unsupported Request instance passed to criteria builder');
    }

    private static function listCountriesRequest(ListCountriesRequest $request)
    {
        $criteria = [];
        if ($request->getName()) {
            $criteria['name.'.$request->getLocale()] = $request->getName();
        }
        if ($request->getAlpha2()) {
            $criteria['ISOCodes.alpha2Code'] = strtoupper($request->getAlpha2());
        }
        //TODO: lat/lng criteria
        return $criteria;
    }
}
