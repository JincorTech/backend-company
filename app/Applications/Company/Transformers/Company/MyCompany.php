<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 05/04/2017
 * Time: 14:49
 */

namespace App\Applications\Company\Transformers\Company;


use App\Applications\Company\Transformers\Dictionary\CompanyTypeTransformer;
use App\Applications\Company\Transformers\Dictionary\CountryTransformer;
use App\Applications\Company\Transformers\Dictionary\EconomicalActivityTypeTransformer;
use App\Core\Dictionary\Entities\Country;
use App\Domains\Company\Entities\CompanyType;
use App\Core\ValueObjects\TranslatableString;
use App\Domains\Company\Entities\Company;
use Doctrine\Common\Collections\ArrayCollection;
use League\Fractal\TransformerAbstract;

class MyCompany extends TransformerAbstract
{


    public function transform(Company $company)
    {
        return [
            'id' => $company->getId(),
            'legalName' => $company->getProfile()->getName(),
            'profile' => [
                'brandName' => $this->transformBrandName($company->getProfile()->getBrandName()),
                'links' => $this->transformLinks($company->getProfile()->getLinks()),
                'email' => $company->getProfile()->getEmail(),
                'phone' => $company->getProfile()->getPhone(),
                'country' => $this->transformCountry($company->getProfile()->getAddress()->getCountry()),
                'city' => null, //TODO: implement city transformation
            ],
            'economicalActivityTypes' => $this->transformEconomicalActivities(
                $company->getProfile()->getEconomicalActivities()
            ),
            'companyType' => $this->transformCompanyType($company->getProfile()->getType()),
        ];
    }


    /**
     * @param Country $country
     * @return array
     */
    protected function transformCountry(Country $country)
    {
        return (new CountryTransformer())->transform($country);
    }

    /**
     * @param ArrayCollection $links
     * @return array
     */
    protected function transformLinks(ArrayCollection $links)
    {
        $result = [];
        $transformer = new CompanyLink();
        foreach ($links as $link) {
            $result[] = $transformer->transform($link);
        }
        return $result;
    }

    /**
     * @param TranslatableString $brandName
     * @return array
     */
    protected function transformBrandName($brandName) : array
    {
        if ($brandName instanceof TranslatableString) {
            return $brandName->getValues();
        }
        return [];
    }

    /**
     * @param ArrayCollection $types
     * @return array
     */
    protected function transformEconomicalActivities(ArrayCollection $types) : array
    {
        $result = [];
        foreach ($types as $type) {
            $result[] = (new EconomicalActivityTypeTransformer())->transform($type, false);
        }
        return $result;
    }

    /**
     * @param CompanyType $companyType
     * @return array
     */
    protected function transformCompanyType(CompanyType $companyType) : array
    {
        return (new CompanyTypeTransformer())->transform($companyType);
    }


}