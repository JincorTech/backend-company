<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/30/16
 * Time: 1:57 AM
 */

namespace App\Domains\Company\Services;

use App\Core\Dictionary\Repositories\CountryRepository;
use App\Core\Services\AddressService;
use App\Domains\Company\Entities\Company;
use App\Domains\Company\Entities\EmployeeVerification;
use App\Domains\Company\Repositories\EmployeeVerificationRepository;
use App\Domains\Company\ValueObjects\CompanyProfile;
use App;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Company\Entities\CompanyType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyRegistrationService
{
    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var AddressService
     */
    private $addressService;

    /**
     * @var EmployeeVerificationService
     */
    private $employeeVerificationService;

    public function __construct(AddressService $addressService, EmployeeVerificationService $verificationService)
    {
        $this->addressService = $addressService;
        $this->employeeVerificationService = $verificationService;
    }

    public function register(
        string $country,
        string $legalName,
        string $companyType
    ) {
        $address = $this->addressService->build($country);
        $ct = $this->getDm()->getRepository(CompanyType::class)->find($companyType);
        if (!$ct) {
            throw new NotFoundHttpException(trans('registration-messages.typeNotFound', [
                'ct' => $companyType,
            ]));
        }
        $company = new Company($legalName, $address, $ct);
        $this->getDm()->persist($company);

        return $this->employeeVerificationService->beginVerificationProcess($company);
    }

    private function getDm()
    {
        if (!$this->dm) {
            $this->dm = App::make(DocumentManager::class);
        }

        return $this->dm;
    }
}
