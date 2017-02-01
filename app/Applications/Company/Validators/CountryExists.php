<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/30/16
 * Time: 2:26 AM
 */

namespace App\Applications\Company\Validators;

use App\Core\Dictionary\Repositories\CountryRepository;
use Illuminate\Validation\Validator;

class CountryExists
{
    /**
     * @var CountryRepository
     */
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param Validator $validator
     * @return bool
     */
    public function validate($attribute, $value, $parameters, $validator)
    {
        $country = $this->countryRepository->find($value);

        return $country !== null;
    }
}
