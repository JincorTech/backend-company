<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/18/16
 * Time: 4:03 PM
 */

namespace App\Applications\Company\Http\Controllers;

use App\Applications\Company\Http\Requests\Employee\SendRestorePasswordEmail;
use App\Applications\Company\Transformers\EmployeeVerificationTransformer;
use App\Applications\Company\Http\Requests\Employee\SendVerificationCode;
use App\Applications\Company\Http\Requests\Employee\MatchingCompanies;
use App\Applications\Company\Http\Requests\Employee\ChangePassword;
use App\Applications\Company\Transformers\EmployeeRegisterSuccess;
use App\Applications\Company\Http\Requests\Employee\VerifyByCode;
use App\Applications\Company\Transformers\EmployeeTransformer;
use App\Applications\Company\Http\Requests\Employee\Register;
use App\Applications\Company\Transformers\CompanyTransformer;
use App\Domains\Employee\Services\EmployeeRegistrationService;
use App\Domains\Employee\Services\EmployeeVerificationService;
use App\Applications\Company\Http\Requests\Employee\Login;
use App\Domains\Employee\Services\EmployeeService;
use App\Core\Services\IdentityService;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use Dingo\Api\Http\Response;
use App;

class EmployeeController extends BaseController
{

    /**
     * @var IdentityService
     */
    private $identityService;

    /**
     * @var EmployeeService
     */
    private $employeeService;

    /**
     * @var EmployeeVerificationService
     */
    private $verificationService;

    /**
     * EmployeeController constructor.
     *
     * @param EmployeeService $employeeService
     * @param IdentityService $identityService
     */
    public function __construct(EmployeeService $employeeService, IdentityService $identityService, EmployeeVerificationService $verificationService)
    {
        $this->employeeService = $employeeService;
        $this->identityService = $identityService;
        $this->verificationService = $verificationService;
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
     * @param Register $request
     *
     * @return Response
     */
    public function register(Register $request)
    {
        $employee = $this->employeeService->register(
            $request->getVerificationId(),
            $request->getProfile(),
            $request->getPassword()
        );
        $token = $this->identityService->login(
            $employee->getContacts()->getEmail(),
            $request->getPassword(),
            $employee->getCompany()->getId()
        );
        return $this->response->item(new Collection(['employee' => $employee, 'token' => $token]), EmployeeRegisterSuccess::class);
    }


    public function sendRestorePasswordEmail(SendRestorePasswordEmail $request)
    {
        $verification = $this->verificationService->sendEmailRestorePassword($request->getEmail())->getVerification();
        return $this->response->item($verification, new EmployeeVerificationTransformer());
    }


    /**
     * Force password change
     *
     * @param ChangePassword $request
     * @return Response
     */
    public function changePassword(ChangePassword $request)
    {
        $oldPassword = null;
        if ($request->getVerificationId()) {
            $employee = $this->employeeService->matchVerificationAndCompany(
                $request->getVerificationId(),
                $request->getCompanyId()
            );
        } else {
                $employee = App::make('AppUser');
                $oldPassword = $request->getOldPassword();
        }
        $this->employeeService->changePassword(
            $employee,
            $request->getPassword(),
            $oldPassword
        );
        return $this->response->item($employee, new EmployeeTransformer());
    }

    /**
     * @param MatchingCompanies $request
     * @return Response
     */
    public function matchingCompanies(MatchingCompanies $request)
    {
        $companies = $this->employeeService->getMatchingCompanies([
            'email' => $request->getEmail(),
            'password' => $request->getPassword(),
            'verificationId' => $request->getVerificationId(),
        ]);
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

    public function me()
    {
        $employee = App::make('AppUser');

    }

}
