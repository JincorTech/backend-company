<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 12/6/16
 * Time: 11:54 PM
 */

namespace App\Core\Services\INNInfo;

interface INNInfoInterface
{
    /**
     * @param string $INN
     * @return array
     */
    public function getInfoByINN(string $INN);
}
