<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 05/04/2017
 * Time: 15:00
 */

namespace App\Applications\Company\Transformers\Company;

use App\Domains\Company\ValueObjects\CompanyExternalLink;
use League\Fractal\TransformerAbstract;

class CompanyLink extends TransformerAbstract
{

    public function transform(CompanyExternalLink $link)
    {
        //TODO: implement transformation
        return [
        ];
    }

}