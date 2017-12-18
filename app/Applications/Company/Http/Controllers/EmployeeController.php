<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/18/16
 * Time: 4:03 PM
 */

namespace App\Applications\Company\Http\Controllers;

use App\Applications\Company\Exceptions\Employee\EmployeeVerificationException;
use App\Applications\Company\Exceptions\Employee\InvalidEmailSpecified;
use App\Applications\Company\Http\Requests\Employee\SendRestorePasswordEmail;
use App\Applications\Company\Interfaces\Company\CompanyServiceInterface;
use App\Applications\Company\Services\Employee\EmployeeVerificationService;
use App\Applications\Company\Transformers\EmployeeVerificationTransformer;
use App\Applications\Company\Transformers\Employee\SearchEmployeeContact;
use App\Applications\Company\Http\Requests\Employee\SendVerificationCode;
use App\Applications\Company\Transformers\Employee\EmployeeContactList;
use App\Applications\Company\Http\Controllers\Traits\PaginatedResponse;
use App\Applications\Company\Http\Requests\Employee\MatchingCompanies;
use App\Applications\Company\Exceptions\Employee\EmployeeNotActivated;
use App\Applications\Company\Transformers\Company\CompanyTransformer;
use App\Applications\Company\Http\Requests\Employee\SearchContacts;
use App\Applications\Company\Http\Requests\Employee\GetContactList;
use App\Applications\Company\Http\Requests\Employee\ChangePassword;
use App\Applications\Company\Http\Requests\Employee\ListByMatrixId;
use App\Core\Interfaces\EmployeeVerificationReason;
use App\Core\Services\Exceptions\MultipleCompanyLoginException;
use App\Applications\Company\Transformers\EmployeeRegisterSuccess;
use App\Applications\Company\Http\Requests\Employee\UpdateRequest;
use App\Applications\Company\Http\Requests\Employee\DeleteContact;
use App\Applications\Company\Http\Requests\Employee\VerifyByCode;
use App\Applications\Company\Transformers\Employee\ColleagueList;
use App\Applications\Company\Transformers\Employee\LoginResponse;
use App\Applications\Company\Transformers\Employee\SelfProfile;
use App\Applications\Company\Http\Requests\Employee\Colleagues;
use App\Applications\Company\Transformers\Employee\ContactList;
use App\Applications\Company\Services\Employee\EmployeeService;
use App\Applications\Company\Http\Requests\Employee\AddContact;
use App\Applications\Company\Http\Requests\Employee\MakeAdmin;
use App\Applications\Company\Transformers\Employee\Colleague;
use App\Applications\Company\Http\Requests\Employee\Register;
use App\Applications\Company\Http\Requests\Employee\Delete;
use App\Applications\Company\Http\Requests\Employee\Login;
use App\Applications\Company\Http\Requests\Employee\Me;
use App\Applications\Company\Exceptions\Employee\PermissionDenied;
use App\Applications\Company\Exceptions\Employee\EmployeeNotFound;
use Doctrine\Common\Collections\ArrayCollection;
use App\Core\Interfaces\IdentityInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use Dingo\Api\Http\Response;
use App\Applications\Company\Http\Requests\Employee\QueryLogins;
use App\Applications\Company\Transformers\Employee\EmployeeList;
use App;
use JincorTech\VerifyClient\Exceptions\InvalidCodeException;

class EmployeeController extends BaseController
{
    use PaginatedResponse;

    /**
     * @var IdentityInterface
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
     * @var App\Applications\Company\Services\Company\CompanyServiceInterface
     */
    private $companyService;

    /**
     * EmployeeController constructor.
     *
     * @param EmployeeService $employeeService
     * @param IdentityInterface $identityService
     * @param EmployeeVerificationService $verificationService
     * @param CompanyServiceInterface $companyService
     */
    public function __construct(
        EmployeeService $employeeService,
        IdentityInterface $identityService,
        EmployeeVerificationService $verificationService,
        CompanyServiceInterface $companyService
    ) {
        $this->employeeService = $employeeService;
        $this->identityService = $identityService;
        $this->verificationService = $verificationService;
        $this->companyService = $companyService;
    }

    /**
     * @param SendVerificationCode $request
     *
     * @return Response
     */
    public function sendEmailCode(SendVerificationCode $request)
    {
        $verification = $this->verificationService->sendEmailVerification($request->getVerificationId());
        return $this->response->item($verification, EmployeeVerificationTransformer::class);
    }

    /**
     * @param VerifyByCode $request
     *
     * @return Response|JsonResponse
     */
    public function verifyEmail(VerifyByCode $request)
    {
        try {
            $verification = $this->verificationService->verifyEmail(
                $request->getVerificationId(),
                $request->getVerificationCode()
            );

            if ($verification->getReason() === EmployeeVerificationReason::REASON_REGISTER) {
                $this->employeeService->activate(
                    $verification
                );
            }

            return $this->response->item($verification, EmployeeVerificationTransformer::class);
        } catch (InvalidCodeException $e) {
            return new JsonResponse([
                'success' => false,
                'message' => trans('exceptions.verification.code.incorrect'),
            ], 401);
        }
    }


    /**
     * @param Register $request
     *
     * @return JsonResponse
     */
    public function register(Register $request)
    {
        try {
            $registerResult = $this->employeeService->register(
                $request->getToken(),
                $request->getEmail(),
                $request->getProfile(),
                $request->getPassword()
            );

            $employee = $registerResult->getEmployee();

            $token = $this->identityService->login(
                $employee->getContacts()->getEmail(),
                $request->getPassword(),
                $employee->getCompany()->getId()
            );

            return new JsonResponse(
                (new EmployeeRegisterSuccess())
                    ->transform(Collection::make([
                        'employee' => $employee,
                        'token' => $token,
                        'verificationId' => $registerResult->getVerificationId()
                    ]))
            );

        } catch (InvalidEmailSpecified $exception) {
            $this->response->error($exception->getMessage(), 422);
        } catch (EmployeeVerificationException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        }
    }


    /**
     * @param SendRestorePasswordEmail $request
     * @return Response
     */
    public function sendRestorePasswordEmail(SendRestorePasswordEmail $request)
    {
        $verification = $this->verificationService->sendEmailRestorePassword($request->getEmail());
        return $this->response->item($verification, EmployeeVerificationTransformer::class);
    }


    /**
     * @param Login $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Login $request)
    {
        try {
            $token = $this->identityService->login(
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
        if ($token !== false) {
            /** @var App\Domains\Company\Entities\Company $company */
            if (!$request->getCompanyId()) {
                $company = $this->employeeService->getMatchingCompanies([
                    'email' => $request->getEmail(),
                    'password' => $request->getPassword()
                ])->first();
            } else {
                $company = $this->companyService->getCompany($request->getCompanyId());
            }
            $employee = $this->employeeService->findByCompanyIdAndEmail($company->getId(), $request->getEmail());
            if(!$employee) {
                throw new EmployeeNotFound;
            }
            if (!$employee->isActive()) {
                throw new EmployeeNotActivated;
            }
            $data = (object) [
                'token' => $token,
                'employee' => $employee,
            ];
            return $this->response->item($data, LoginResponse::class)->withHeader('Access-Control-Allow-Origin', '*');
        }
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
        $token = $this->identityService->login(
            $employee->getContacts()->getEmail(),
            $request->getPassword(),
            $request->getCompanyId()
        );
        $data = (object) [
            'token' => $token,
            'employee' => $employee,
        ];
        return $this->response->item($data, LoginResponse::class);
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
     * @param Me $request
     * @return Response
     */
    public function me(Me $request)
    {
        return $this->response->item($request->getUser(), SelfProfile::class);
    }


    /**
     * @param MakeAdmin $request
     * @return Response
     */
    public function makeAdmin(MakeAdmin $request)
    {
        try {
            return $this->response->item(
                $this->employeeService->makeAdmin(
                    $request->getUser(),
                    $request->get('id'),
                    $request->get('value')
                ),
                Colleague::class
            );
        } catch (PermissionDenied $exception) {
            $this->response->error($exception->getMessage(), 403);
        } catch (EmployeeNotFound $exception) {
            $this->response->error($exception->getMessage(), 404);
        }
    }

    /**
     * @param Delete $request
     * @param string $id
     * @return Response
     */
    public function delete(Delete $request, string $id)
    {
        try {
            return $this->response->item(
                $this->employeeService->deactivate($request->getUser(), $id),
                Colleague::class
            );
        } catch (PermissionDenied $exception) {
            $this->response->error($exception->getMessage(), 403);
        } catch (EmployeeNotFound $exception) {
            $this->response->error($exception->getMessage(), 404);
        }
    }

    /**
     * @param UpdateRequest $request
     * @return Response
     */
    public function update(UpdateRequest $request)
    {
        try {
            return $this->response->item(
                $this->employeeService->updateEmployee($request->getUser(), $request->get('profile')),
                SelfProfile::class
            );
        } catch (App\Core\Exceptions\InvalidImageException $exception) {
            $this->response->error($exception->getMessage(), 422);
        }
    }

    /**
     * @param Colleagues $request
     * @return JsonResponse
     */
    public function colleagues(Colleagues $request)
    {
        $response = Collection::make($this->employeeService->getColleagues($request->getUser()));
        return new JsonResponse((new ColleagueList())->transform($response));
//        return $this->response->item($response, ColleagueList::class);
    }

    public function getContactList(GetContactList $request)
    {
        $contacts = $this->employeeService->getContactList();
        return $this->paginatedResponse($request, $contacts, ContactList::class);
    }

    public function addContact(AddContact $request)
    {
        $contact = $this->employeeService->addContact(
            $request->get('email'),
            $request->get('companyId')
        );

        return $this->response->item($contact, EmployeeContactList::class);
    }

    public function deleteContact(DeleteContact $request, string $id)
    {
        $contact = $this->employeeService->deleteContact($id);
        return $this->response->item($contact, EmployeeContactList::class);
    }

    public function searchContacts(SearchContacts $request)
    {
        $email = $request->get('email');
        /**
         * @var $foundEmployees ArrayCollection
         */
        $foundEmployees = $this->employeeService->findByEmail($email);
        return $this->paginatedResponse($request, $foundEmployees, SearchEmployeeContact::class);
    }


    public function matrix(ListByMatrixId $request)
    {
        return $this->response->collection(
            $this->employeeService->findByMatrixIds($request->getMatrixIds()),
            EmployeeContactList::class
        );
    }
}
