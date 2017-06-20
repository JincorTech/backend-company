<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 19.06.17
 * Time: 14:47
 */

namespace App\Domains\Employee\Interfaces;

use App\Domains\Employee\Entities\Employee;
use App\Domains\Employee\ValueObjects\EmployeeProfile;
use Illuminate\Support\Collection;
use App\Domains\Employee\Entities\EmployeeVerification;

interface EmployeeServiceInterface
{
    public function register(
        string $verificationId,
        EmployeeProfile $profile,
        string $password
    ) : Employee;

    public function getColleagues(Employee $employee);

    public function findById(string $id) : Employee;

    public function findByEmail(string $email) : Collection;

    public function findByLogin(string $login);

    public function findByCompanyIdAndEmail(string $id, string $email);

    public function findByEmailAndPassword(string $email, string $password) : Collection;

    public function findByVerificationId(string $verificationId) : Collection;

    public function getMatchingCompanies(array $options) : Collection;

    public function getEmployeesCompanies(Collection $employees) : Collection;

    public function matchVerificationAndCompany(string $verificationId, string $companyId) : Employee;

    public function changePassword(Employee $employee, string $newPassword, $oldPassword = null);

    public function deactivate(Employee $admin, string $id);

    public function invite(string $email, Employee $inviter) : EmployeeVerification;

    public function inviteMany(Collection $invitees, Employee $inviter);

    public function updateEmployee(Employee $employee, array $data);

    public function makeAdmin(Employee $admin, string $id, bool $value);

}
