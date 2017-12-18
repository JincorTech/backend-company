<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/31/17
 * Time: 2:36 PM
 */

namespace App\Core\Http\Middleware;

use Closure;
use App;
use App\Applications\Company\Services\Employee\EmployeeService;
use App\Domains\Employee\Entities\Employee;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use App\Core\Interfaces\IdentityInterface;
use App\Core\Interfaces\WalletsServiceInterface;

class GetEmployeeFromRequest
{
    protected $identityService;
    protected $employeeService;
    /**
     * @var WalletsServiceInterface
     */
    private $walletsService;

    /**
     * GetEmployeeFromRequest constructor.
     * @param IdentityInterface $identityService
     * @param EmployeeService $employeeService
     */
    public function __construct(IdentityInterface $identityService, EmployeeService $employeeService, WalletsServiceInterface $walletsService)
    {
        $this->identityService = $identityService;
        $this->employeeService = $employeeService;
        $this->walletsService = $walletsService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     *
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $header = $this->validateHeader($request);
        if ($header === false) {
            return $next($request);
        }
        $data = $this->identityService->validateToken($header);
        if ($data === false) {
            throw new AuthenticationException("JWT is invalid");
        }

        /** @var \App\Domains\Employee\Entities\Employee $employee */
        $employee = $this->employeeService->findByLogin($data->getLogin());
        if (!$employee || !$employee->isActive()) {
            return new JsonResponse([
                'success' => false,
                'errors' => [
                    'token' => 'Authentication token is invalid',
                ],
                'message' => 'Cant find user by login. It means that your access token is invalid',
            ], 401);
        }
        $employee->getProfile()->scope = $data->getScope();
        $company = $employee->getCompany();
        if ($request->getRequestUri() !== '/api/v1/company/my') {
            //TODO: refactor, move to the event handler or some other better place
            if (!$employee->getWallets()) {
                $employeeWallets = $this->walletsService->register([], $header);
                if ($employeeWallets) {
                    $employee->setWallets($employeeWallets);
                    $this->employeeService->dm->persist($employee);
                }
            }
            if ($employee->isAdmin() && !$company->getWallets()) {
                $companyWallets = $this->walletsService->registerCorporate([], $header);
                if ($companyWallets) {
                    $company->setWallets($companyWallets);
                    $this->employeeService->dm->persist($company);
                }
            }
        }
        $this->employeeService->dm->flush();
        //END TODO


        $this->bindUser($employee);

        return $next($request);
    }

    private function bindUser(Employee $employee)
    {
        App::instance('AppUser', $employee);
    }

    public function validateHeader($request)
    {
        $header = $request->header('Authorization');
        if (!$header) {
            return false;
        }
        $parts = explode(' ', $header);
        if (count($parts) !== 2) {
            return false;
        }

        return $parts[1];
    }
}
