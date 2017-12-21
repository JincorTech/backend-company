<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/30/17
 * Time: 4:21 PM
 */

namespace App\Core\Services;

use App\Core\Interfaces\IdentityInterface;
use App\Core\Services\Exceptions\MultipleCompanyLoginException;
use App\Core\Services\Exceptions\PasswordMismatchException;
use App\Applications\Company\Interfaces\Employee\EmployeeServiceInterface;
use App\Applications\Company\Interfaces\Company\CompanyServiceInterface;
use Doctrine\ODM\MongoDB\DocumentNotFoundException;
use Illuminate\Support\Collection;
use App;
use JincorTech\AuthClient\AuthServiceInterface;

class IdentityService implements IdentityInterface
{
    private $companyService;
    private $employeeService;
    private $authClient;

    /**
     * IdentityService constructor.
     * @param EmployeeServiceInterface $employeeService
     * @param CompanyServiceInterface $companyService
     * @param AuthServiceInterface $authClient
     */
    public function __construct(
        EmployeeServiceInterface $employeeService,
        CompanyServiceInterface $companyService,
        AuthServiceInterface $authClient
    )
    {
        $this->authClient = $authClient;
        $this->companyService = $companyService;
        $this->employeeService = $employeeService;
    }

    public function register(array $data)
    {
        return $this->authClient->createUser($data, config('services.identity.jwt'));
    }


    /**
     * @param string $userToken
     * @return \JincorTech\AuthClient\UserTokenVerificationResult
     */
    public function validateToken(string $userToken)
    {
        $result = $this->authClient->verifyUserToken($userToken, config('services.identity.jwt'));
        return $result;
    }

    /**
     * @param string $email
     * @param string $password
     * @return Collection
     */
    public function getMatchingCompanies(string $email, string $password)
    {
        $employees = $this->employeeService->findByEmailAndPassword($email, $password);

        return $this->employeeService->getEmployeesCompanies($employees);
    }

    /**
     * @param string $email
     * @param string $password
     * @param string|null $company
     * @return bool|mixed
     * @throws DocumentNotFoundException
     * @throws PasswordMismatchException
     * @throws MultipleCompanyLoginException
     */
    public function login(string $email, string $password, $company)
    {
        if (!$company) {
            $companies = $this->getMatchingCompanies($email, $password);
            if ($companies->count() === 0) {
                throw new DocumentNotFoundException(trans('exceptions.company.not_found'));
            }
            if ($companies->count() > 1) {
                throw new MultipleCompanyLoginException(trans('exceptions.login.multiple-companies'));
            }
            $company = $companies->first()->getId();
        }
        $employee = $this->employeeService->findByCompanyIdAndEmail($company, $email);
        if (!$employee) {
            throw new DocumentNotFoundException(trans('exceptions.employee.not_found', ['email' => $email]));
        }
        if (!$employee->checkPassword($password)) {
            throw new PasswordMismatchException(trans('exceptions.employee.password_mismatch'));
        }
        return $this->authClient->loginUser([
            'login' => $company.':'.$email,
            'password' => $employee->getPassword(),
            'deviceId' => '12345',
        ], config('services.identity.jwt'));
    }

    public function logout()
    {
    }
}
