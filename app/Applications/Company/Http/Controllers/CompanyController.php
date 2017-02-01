<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/18/16
 * Time: 4:00 PM
 *
 * Company controller controls all the processes connected to the company in this application
 */

namespace App\Applications\Company\Http\Controllers;

use App\Applications\Company\Http\Requests\PublicDictionaryRequest;
use App\Applications\Company\Http\Requests\Company\RegisterCompany;
use App\Applications\Company\Http\Requests\PublicEconomicalActivityTypesRequest;
use App\Applications\Company\Transformers\CompanyTransformer;
use App\Applications\Company\Transformers\CompanyTypeTransformer;
use App\Applications\Company\Transformers\EconomicalActivityTypeTransformer;
use App\Applications\Company\Transformers\EmployeeVerificationTransformer;
use App\Domains\Company\Services\CompanyRegistrationService;
use App\Domains\Company\Services\CompanyService;
use Dingo\Api\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CompanyController extends BaseController
{
    /**
     * @var CompanyRegistrationService
     */
    private $registrationService;

    /**
     * @var CompanyService
     */
    private $companyService;

    /**
     * CompanyController constructor.
     * @param CompanyRegistrationService $registrationService
     * @param CompanyService $companyService
     */
    public function __construct(CompanyRegistrationService $registrationService, CompanyService $companyService)
    {
        $this->registrationService = $registrationService;
        $this->companyService = $companyService;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function info(Request $request, $id)
    {
        $company = $this->companyService->getCompany($id);
        if (!$company) {
            $this->response->errorNotFound(trans('companies.errors.notFound', [
                'cId' => $id,
            ]));
        }

        return $this->response->item($company, new CompanyTransformer());
    }

    public function invite()
    {

    }

    /**
     * Register new company.
     *
     * @param RegisterCompany $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(RegisterCompany $request)
    {
        $verification = $this->registrationService->register(
            $request->getCountryId(),
            $request->getLegalName(),
            $request->getCompanyTypeId()
        );
        $transformer = new EmployeeVerificationTransformer();

        return $this->response->created(
            '/api/v1/company/'.$verification->getCompany()->getId(),
            $transformer->transform($verification)
        );
    }

    /**
     * @param PublicDictionaryRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function companyTypes(PublicDictionaryRequest $request)
    {
        $companyTypes = new Collection($this->companyService->getCompanyTypes());

        return $this->response->collection($companyTypes, new CompanyTypeTransformer($request->getLocale()));
    }

    public function economicalActivityTypes(PublicEconomicalActivityTypesRequest $typesRequest)
    {
        $types = $this->companyService->getEARoot();
        $responseTypes = new Collection();
        foreach ($types as $type) {
            $responseTypes->push($type);
        }
        $transformer = new EconomicalActivityTypeTransformer($typesRequest->getLocale());
        $paginator = new LengthAwarePaginator($responseTypes, $responseTypes->count(), config('view.perPage'));

        return $this->response->paginator($paginator, $transformer);
    }
}
