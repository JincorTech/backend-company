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
use App\Applications\Company\Transformers\Company\CompanyTransformer;
use App\Applications\Company\Http\Requests\Employee\ChangePassword;
use App\Domains\Employee\Exceptions\MultipleCompanyLoginException;
use App\Applications\Company\Transformers\EmployeeRegisterSuccess;
use App\Applications\Company\Http\Requests\Employee\VerifyByCode;
use App\Applications\Company\Transformers\Employee\SelfProfile;
use App\Applications\Company\Http\Requests\Employee\Colleagues;
use App\Applications\Company\Transformers\EmployeeTransformer;
use App\Domains\Employee\Services\EmployeeVerificationService;
use App\Applications\Company\Http\Requests\Employee\Register;
use App\Applications\Company\Transformers\Employee\Colleague;
use App\Applications\Company\Http\Requests\Employee\Login;
use App\Applications\Company\Http\Requests\Employee\Me;
use App\Applications\Company\Http\Requests\Employee\UpdateRequest;
use App\Domains\Employee\Services\EmployeeService;
use App\Core\Services\IdentityService;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use Dingo\Api\Http\Response;
use App;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;

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
     * @param EmployeeVerificationService $verificationService
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
        return $this->response->item($verification, EmployeeVerificationTransformer::class);
    }

    /**
     * @param VerifyByCode $request
     *
     * @return Response
     */
    public function verifyEmail(VerifyByCode $request)
    {
        $verification = $this->verificationService->verifyEmail($request->getVerificationId(), $request->getVerificationCode());
        return $this->response->item($verification, EmployeeVerificationTransformer::class);
    }


    /**
     * @param Register $request
     *
     * @return JsonResponse
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
        return new JsonResponse(
            (new EmployeeRegisterSuccess())
                ->transform(Collection::make([
                    'employee' => $employee, 'token' => $token,
            ]))
        );
    }


    /**
     * @param SendRestorePasswordEmail $request
     * @return Response
     */
    public function sendRestorePasswordEmail(SendRestorePasswordEmail $request)
    {
        $verification = $this->verificationService->sendEmailRestorePassword($request->getEmail())->getVerification();
        return $this->response->item($verification, EmployeeVerificationTransformer::class);
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
        return $this->response->item($employee, EmployeeTransformer::class);
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
        return $this->response->collection($companies, CompanyTransformer::class);
    }


    /**
     * @param Login $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Login $request)
    {
        try {
            $result = $this->identityService->login(
                $request->getEmail(),
                $request->getPassword(),
                $request->getCompanyId()
            );
        } catch (MultipleCompanyLoginException $exception) {
            $companies = $this->employeeService->getMatchingCompanies([
                'email' => $request->getEmail(),
                'password' => $request->getPassword()
            ]);
            return $this->response->collection($companies, CompanyTransformer::class);
        }
        if ($result !== false) {
            return new JsonResponse([
                'data' => $result,
            ]);
        }
    }

    /**
     * @param Me $request
     * @return Response
     */
    public function me(Me $request)
    {
        return $this->response->item($request->getUser(), SelfProfile::class);
    }

    /**
     * @param UpdateRequest $request
     * @return Response
     */
    public function update(UpdateRequest $request)
    {
        return $this->response->item(
            $this->employeeService->updateEmployee($request->getUser(),$request->all()->toArray()),
            SelfProfile::class
        );
    }

    /**
     * @param Colleagues $request
     * @return Response
     */
    public function colleagues(Colleagues $request)
    {
        $response = Collection::make($this->employeeService->getColleagues($request->getUser())->toArray());
        return $this->response->collection($response, Colleague::class);
    }


    public function testFileUpload(Request $request)
    {
        $avatar = $request->get('avatar');
        $filePath = 'avatars/' . uniqid('empl_') . '.' . 'png';
        return new JsonResponse(['result' => App::make(App\Core\Services\ImageService::class)->upload($filePath, $avatar)]);
    }
}
