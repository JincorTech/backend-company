<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/30/17
 * Time: 4:21 PM
 */

namespace App\Core\Services;

use App\Domains\Company\Entities\Employee;
use App\Domains\Company\Exceptions\PasswordMismatchException;
use App\Domains\Company\Services\CompanyService;
use App\Domains\Company\Services\EmployeeService;
use Doctrine\ODM\MongoDB\DocumentNotFoundException;
use Illuminate\Support\Collection;

class IdentityService extends BaseRestService
{
    private $companyService;
    private $employeeService;

    public function __construct()
    {
        parent::__construct(config('services.identity.uri'));
        $this->companyService = new CompanyService();
        $this->employeeService = new EmployeeService();
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

    public function login(string $email, string $password, string $company)
    {
        $employee = $this->employeeService->findByCompanyIdAndEmail($company, $email);
        if (!$employee) {
            throw new DocumentNotFoundException('Employee not found');
        }
        if (!$employee->checkPassword($password)) {
            throw new PasswordMismatchException('Login and password do not match');
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
                return $data;
            }

            return false;
        }
    }

    public function logout()
    {
    }
}
