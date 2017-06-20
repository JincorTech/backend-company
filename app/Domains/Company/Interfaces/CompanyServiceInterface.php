<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 19.06.17
 * Time: 18:09
 */

namespace App\Domains\Company\Interfaces;
use App\Domains\Company\Entities\Company;

interface CompanyServiceInterface
{
    public function register(
        string $country,
        string $legalName,
        string $companyType
    );

    public function update(Company $company, array $data);

    public function uploadImage(Company $company, $data);

    public function search($query = null, $country = null, $activity = null);

    public function getCompany(string $id);

    public function getCompanyTypes();

    public function getEconomicalActivityTypes();

    public function getEARoot();

}
