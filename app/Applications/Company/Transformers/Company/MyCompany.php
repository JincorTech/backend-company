<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 31/03/2017
 * Time: 10:41
 */

namespace App\Applications\Company\Transformers\Company;


use App\Core\ValueObjects\Address;
use App\Domains\Company\Entities\Company;
use App\Domains\Company\ValueObjects\CompanyProfile;
use League\Fractal\TransformerAbstract;

class MyCompany extends TransformerAbstract
{
    /**
     * @var ExternalLink
     */
    private $link;

    public function __construct()
    {
        $this->link = new ExternalLink();
    }

    /**
     * @param Company $company
     * @return array
     */
    public function transform(Company $company) : array
    {
        return [
            'id' => $company->getId(),
            'legalName' => $company->getProfile()->getName(),
            'profile' => $this->transformProfile($company->getProfile()),
            'economicalActivityType' => null,
            'companyType' => null,

        ];
    }

    /**
     * Transform company profile
     *
     * @param CompanyProfile $profile
     * @return array
     */
    private function transformProfile(CompanyProfile $profile) : array
    {
        return [
            'brandName' => $profile->getBrandName(null, true),
            'links' => $this->transformLinks($profile),
            'email' => $profile->getEmail(),
            'phone' => $profile->getPhone(),
            'country' => $this->transformCountry($profile->getAddress()), //TODO: country transformer
            'city' => $this->transformCity($profile->getAddress()), //TODO: region
        ];
    }

    /**
     * Transform external links
     *
     * @param CompanyProfile $profile
     * @return array
     */
    private function transformLinks(CompanyProfile $profile) : array
    {
        $result = [];
        foreach ($profile->getLinks() as $link) {
            $result[] = $this->link->transform($link);
        }
        return $result;
    }

    /**
     * @param CompanyProfile $profile
     * @return array
     */
    private function transformCompanyTypes(CompanyProfile $profile) : array
    {
        return [];
    }

    /**
     * @param CompanyProfile $profile
     * @return array
     */
    private function transformEconomicalActivityTypes(CompanyProfile $profile) : array
    {
        return [];
    }

    /**
     * @param Address $address
     * @return array
     */
    private function transformCountry(Address $address) : array
    {
        return [];
    }

    /**
     * @param Address $address
     * @return array
     */
    private function transformCity(Address $address) : array
    {
        return [];
    }

}