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
use App\Domains\Employee\Exceptions\MultipleCompanyLoginException;
use App\Domains\Employee\Exceptions\PasswordMismatchException;
use App\Applications\Company\Interfaces\Employee\EmployeeServiceInterface;
use App\Applications\Company\Interfaces\Company\CompanyServiceInterface;
use Doctrine\ODM\MongoDB\DocumentNotFoundException;
use Illuminate\Support\Collection;
use App;

class IdentityService extends BaseRestService implements IdentityInterface
{
    private $companyService;
    private $employeeService;

    /**
     * IdentityService constructor.
     * @param EmployeeServiceInterface $employeeService
     * @param CompanyServiceInterface $companyService
     */
    public function __construct(
        EmployeeServiceInterface $employeeService,
        CompanyServiceInterface $companyService
    )
    {
        parent::__construct(config('services.identity.uri'));
        $this->companyService = $companyService;
        $this->employeeService = $employeeService;
    }

    public function register(array $data)
    {
        $response = $this->client->post('/user', [
            'json' => $data,
        ]);

        return $response->getStatusCode() === 200;
    }

    public function validateToken(string $token)
    {
        $response = $this->client->post('/auth/verify', [
            'json' => [
                'token' => $token,
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            if (array_key_exists('decoded', $data)) {
                return $data['decoded'];
            }
        }

        return false;
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
        $response = $this->client->post('/auth', [
            'json' => [
                'login' => $company.':'.$email,
                'password' => $employee->getPassword(),
                'deviceId' => '12345',
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            if (array_key_exists('accessToken', $data)) {
                return $data['accessToken'];
            }

            return false;
        }
    }

    public function logout()
    {
    }
}
