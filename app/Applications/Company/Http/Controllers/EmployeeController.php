<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/18/16
 * Time: 4:03 PM
 */

namespace App\Applications\Company\Http\Controllers;

use App\Applications\Company\Http\Requests\Employee\Login;
use App\Applications\Company\Http\Requests\Employee\Register;
use App\Applications\Company\Http\Requests\Employee\SendVerificationCode;
use App\Applications\Company\Http\Requests\Employee\VerifyByCode;
use App\Applications\Company\Transformers\EmployeeTransformer;
use App\Core\Services\IdentityService;
use App\Domains\Company\Services\EmployeeRegistrationService;
use App\Domains\Company\Services\EmployeeVerificationService;
use App\Domains\Company\Services\EmployeeService;
use App\Applications\Company\Transformers\EmployeeVerificationTransformer;
use App\Core\ValueObjects\EmployeeRole as Role;
use App\Applications\Company\Http\Requests\Employee\MatchingCompanies;
use App\Applications\Company\Transformers\CompanyTransformer;
use Dingo\Api\Http\Response;
use Illuminate\Http\JsonResponse;
use App;

class EmployeeController extends BaseController
{
    /**
     * @var EmployeeVerificationService
     */
    private $verificationService;

    /**
     * @var EmployeeRegistrationService
     */
    private $registrationService;

    /**
     * @var IdentityService
     */
    private $identityService;

    private $employeeService;

    /**
     * EmployeeController constructor.
     * @param EmployeeVerificationService $verificationService
     * @param EmployeeRegistrationService $registrationService
     */
    public function __construct(EmployeeVerificationService $verificationService, EmployeeRegistrationService $registrationService)
    {
        $this->verificationService = $verificationService;
        $this->registrationService = $registrationService;
        $this->identityService = new IdentityService();
        $this->employeeService = new EmployeeService();
    }

    /**
     * @param SendVerificationCode $request
     *
     * @return Response
     */
    public function sendEmailCode(SendVerificationCode $request)
    {
        $verification = $this->verificationService->sendEmailVerification($request->getVerificationId(), $request->getEmail());

        return $this->response->item($verification, new EmployeeVerificationTransformer());
    }

    /**
     * @param VerifyByCode $request
     *
     * @return Response
     */
    public function verifyEmail(VerifyByCode $request)
    {
        $verification = $this->verificationService->verifyEmail($request->getVerificationId(), $request->getVerificationCode());

        return $this->response->item($verification, new EmployeeVerificationTransformer());
    }

    /**
     * @param SendVerificationCode $request
     *
     * @return Response
     */
    public function sendPhoneCode(SendVerificationCode $request)
    {
        $verification = $this->verificationService->sendPhoneVerification($request->getVerificationId(), $request->getPhone());

        return $this->response->item($verification, new EmployeeVerificationTransformer());
    }

    /**
     * @param VerifyByCode $request
     *
     * @return Response
     */
    public function verifyPhone(VerifyByCode $request)
    {
        $verification = $this->verificationService->verifyPhone($request->getVerificationId(), $request->getVerificationCode());

        return $this->response->item($verification, new EmployeeVerificationTransformer());
    }

    /**
     * @param Register $request
     * @return Response
     */
    public function register(Register $request)
    {
        $employee = $this->registrationService->register(
            $request->getVerificationId(),
            $request->getProfile(),
            $request->getPassword()
        );

        return $this->response->item($employee, new EmployeeTransformer());
    }

    /**
     * @param MatchingCompanies $request
     * @return Response
     */
    public function matchingCompanies(MatchingCompanies $request)
    {
        $employees = $this->employeeService->findByEmailAndPassword($request->getEmail(), $request->getPassword());
        $companies = $this->employeeService->getEmployeesCompanies($employees);

        return $this->response->collection($companies, new CompanyTransformer());
    }

    /**
     * @param Login $request
     * @return JsonResponse
     */
    public function login(Login $request)
    {
        $result = $this->identityService->login(
            $request->getEmail(),
            $request->getPassword(),
            $request->getCompanyId()
        );
        if ($result !== false) {
            return new JsonResponse([
                'data' => $result,
            ]);
        }
    }
}
