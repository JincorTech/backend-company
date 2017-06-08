<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 05/04/2017
 * Time: 14:49
 */

namespace App\Applications\Company\Transformers\Company;


use App\Applications\Dictionary\Transformers\CityTransformer;
use App\Applications\Company\Transformers\Dictionary\CompanyTypeTransformer;
use App\Applications\Company\Transformers\Dictionary\CountryTransformer;
use App\Applications\Company\Transformers\Dictionary\EconomicalActivityTypeTransformer;
use App\Core\Dictionary\Entities\City;
use App\Core\Dictionary\Entities\Country;
use App\Core\ValueObjects\Address;
use App\Domains\Company\Entities\CompanyType;
use App\Core\ValueObjects\TranslatableString;
use App\Domains\Company\Entities\Company;
use App\Domains\Company\ValueObjects\CompanyProfile;
use App\Domains\Employee\Entities\Employee;
use Doctrine\Common\Collections\ArrayCollection;
use League\Fractal\TransformerAbstract;

class MyCompany extends TransformerAbstract
{


    public function transform(Company $company)
    {
        return [
            'id' => $company->getId(),
            'legalName' => $company->getProfile()->getName(),
            'profile' => $this->transformProfile($company->getProfile()),
            'economicalActivityTypes' => $this->transformEconomicalActivities(
                $company->getProfile()->getEconomicalActivities()
            ),
            'companyType' => $this->transformCompanyType($company->getProfile()->getType()),
            'employeesCount' => count($company->getEmployees()->partition(function($key, $value) {
                /** @var Employee $value */
                return $value->isActive();
            }))
        ];
    }

    /**
     * @param CompanyProfile $profile
     * @return array
     */
    protected function transformProfile(CompanyProfile $profile)
    {
        return [
            'brandName' => $this->transformBrandName($profile->getBrandName()),
            'description' => $profile->getDescription(),
            'picture' => $profile->getPicture(),
            'links' => $this->transformLinks($profile->getLinks()),
            'email' => $profile->getEmail(),
            'phone' => $profile->getPhone(),
            'address' => $this->transformAddress($profile->getAddress()),
        ];
    }

    /**
     * @param Address $address
     * @return array
     */
    private function transformAddress(Address $address)
    {
        return [
            'country' => $this->transformCountry($address->getCountry()),
            'city' => $address->getCity() ? $this->transformCity($address->getCity()) : null,
            'formattedAddress' => $address->getFormattedAddress(),
        ];
    }

    /**
     * @param City $city
     * @return array
     */
    private function transformCity(City $city)
    {
        return (new CityTransformer())->transform($city);
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
    protected function transformBrandName($brandName)
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