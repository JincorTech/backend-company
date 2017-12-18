<?php

namespace App\Applications\Company\Services\Company;

use App\Domains\Company\Entities\Company;
use InvalidArgumentException;

class CompanyRegistrationResult
{
    /**
     * @var Company
     */
    private $company;

    /**
     * @var string
     */
    private $token;


    /**
     * CompanyRegistrationResult constructor.
     *
     * @param Company $company
     * @param string $token
     */
    public function __construct(Company $company, string $token)
    {
        if (empty($token)) {
            throw new InvalidArgumentException('Token is empty');
        }
        $this->company = $company;
        $this->token = $token;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
