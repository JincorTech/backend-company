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
use App\Core\Services\IdentityService;
use App\Domains\Employee\Services\EmployeeService;
use App\Domains\Employee\Entities\Employee;
use Illuminate\Auth\AuthenticationException;

class GetEmployeeFromRequest
{
    protected $identityService;
    protected $employeeService;

    public function __construct(IdentityService $identityService, EmployeeService $employeeService)
    {
        $this->identityService = $identityService;
        $this->employeeService = $employeeService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
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
        $employee = $this->employeeService->findByLogin($data['login']);
        if (!$employee) {
            throw new AuthenticationException('Cant find user by login. It means that your access token is invalid');
        }
        $employee->getProfile()->scope = $data['scope'];
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
