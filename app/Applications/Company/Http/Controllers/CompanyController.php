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

use App\Applications\Company\Http\Requests\Company\InviteEmployees;
use App\Applications\Company\Http\Requests\PublicEconomicalActivityTypesRequest;
use App\Applications\Company\Transformers\Company\CompanyTransformer;
use App\Applications\Company\Transformers\Company\CompanyType;
use App\Applications\Company\Transformers\Company\EconomicalActivityTypeTransformer;
use App\Applications\Company\Transformers\EmployeeVerificationTransformer;
use App\Applications\Company\Http\Requests\PublicDictionaryRequest;
use App\Applications\Company\Http\Requests\Company\RegisterCompany;
use App\Applications\Company\Transformers\InviteToCompanyResult;
use App\Applications\Company\Http\Requests\Company\MyCompany;
use App\Applications\Company\Transformers\Company\MyCompany as MyCompanyTransformer;
use App\Domains\Employee\Services\EmployeeService;
use App\Domains\Company\Services\CompanyService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use Dingo\Api\Http\Request;
use App;


class CompanyController extends BaseController
{

    /**
     * @var CompanyService
     */
    private $companyService;


    /**
     * @var EmployeeService
     */
    private $employeeService;

    /**
     * CompanyController constructor.
     * @param CompanyService $companyService
     * @param $employeeService EmployeeService
     */
    public function __construct(
        CompanyService $companyService,
        EmployeeService $employeeService
    )
    {
        $this->companyService = $companyService;
        $this->employeeService = $employeeService;

    }

    public function my(MyCompany $request)
    {
        return $this->response->item($request->getUser()->getCompany(), MyCompanyTransformer::class);
    }

    /**
     * @param App\Applications\Company\Http\Requests\Company\MyCompany $request
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function info(MyCompany $request, $id)
    {
        $company = $this->companyService->getCompany($id);
        if (!$company) {
            $this->response->errorNotFound(trans('companies.errors.notFound', [
                'cId' => $id,
            ]));
        }
        return $this->response->item($company, new CompanyTransformer());
    }

    /**
     * @param InviteEmployees $request
     *
     * Invite many employees to the current company
     * @return JsonResponse
     */
    public function invite(InviteEmployees $request)
    {
        $result = $this->employeeService->inviteMany(Collection::make($request->getEmails()), App::make('AppUser'));
        $transformer = new InviteToCompanyResult();
        return new JsonResponse(['data' => $transformer->transform($result)]);
    }

    /**
     * Register new company.
     *
     * @param RegisterCompany $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(RegisterCompany $request)
    {
        $verification = $this->companyService->register(
            $request->getCountryId(),
            $request->getLegalName(),
            $request->getCompanyTypeId()
        );
        $transformer = new EmployeeVerificationTransformer();

        return $this->response->created(
            '/api/v1/company/' . $verification->getCompany()->getId(),
            ['data' => $transformer->transform($verification)]
        );
    }

    /**
     * @param PublicDictionaryRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function companyTypes(PublicDictionaryRequest $request)
    {
        $companyTypes = new Collection($this->companyService->getCompanyTypes());

        return $this->response->collection($companyTypes, new CompanyType($request->getLocale()));
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

