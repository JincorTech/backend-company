<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/10/16
 * Time: 5:07 PM
 */

namespace App\Core\CriteriaBuilders;

use Illuminate\Http\Request;

interface CriteriaBuilderInterface
{
    public static function fromRequest(Request $request);
}
