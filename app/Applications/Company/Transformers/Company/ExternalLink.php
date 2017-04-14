<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 31/03/2017
 * Time: 11:53
 */

namespace App\Applications\Company\Transformers\Company;


use App\Domains\Company\ValueObjects\CompanyExternalLink;
use League\Fractal\TransformerAbstract;

class ExternalLink extends TransformerAbstract
{

    public function transform(CompanyExternalLink $link)
    {
        return [
            'name' => $link->getName(),
            'url' => $link->getUrl()
        ];
    }

}