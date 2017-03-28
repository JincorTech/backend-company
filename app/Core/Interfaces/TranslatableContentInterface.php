<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 10:52 PM
 */

namespace App\Core\Interfaces;


interface TranslatableContentInterface
{


    public function __construct(array $values);

    public function getValue($locale = null) : string;

    public function setValue(string $key, $value);
}