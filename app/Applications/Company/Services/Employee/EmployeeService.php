<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/31/17
 * Time: 1:46 PM
 */

namespace App\Applications\Company\Services\Employee;

use App\Applications\Company\Interfaces\Employee\EmployeeServiceInterface;
use App\Domains\Employee\Exceptions\EmployeeVerificationNotFound;
use App\Domains\Employee\Exceptions\EmployeeVerificationException;
use App\Domains\Employee\Exceptions\EmployeeAlreadyExists;
use App\Domains\Employee\Exceptions\PasswordMismatchException;
use App\Domains\Employee\Exceptions\ContactsNotVerified;
use App\Domains\Employee\Entities\EmployeeVerification;
use App\Domains\Company\Entities\Company;
use App\Domains\Employee\ValueObjects\EmployeeProfile;
use App\Domains\Employee\Entities\Employee;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Employee\Interfaces\EmployeeRepositoryInterface;
use App\Domains\Employee\Interfaces\EmployeeVerificationRepositoryInterface;
use App\Applications\Company\Interfaces\Employee\EmployeeVerificationServiceInterface;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Collection;
use App\Domains\Employee\Exceptions\CompanyNotFound;
use App\Domains\Employee\Exceptions\EmployeeVerificationAlreadySent;
use App\Domains\Employee\Mailables\InviteColleague;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\Domains\Employee\Exceptions\PermissionDenied;
use App\Domains\Employee\Exceptions\InvitationLimitReached;
use App\Domains\Employee\ValueObjects\EmployeeRole;
use App\Core\Services\ImageService;
use App\Domains\Employee\Exceptions\EmployeeNotFound;
use Validator;
use App;
use Mail;

class EmployeeService implements EmployeeServiceInterface
{
    const VERIFICATION_ERR = 'error';
    const VERIFICATION_OK = 'ok';

    /**
     * @var DocumentManager|mixed
     */
    private $dm;

    /**
     * @var \App\Domains\Employee\Interfaces\EmployeeRepositoryInterface | DocumentRepository
     */
    private $repository;

    /**
     * @var EmployeeVerificationRepositoryInterface | DocumentRepository
     */
    private $verificationRepository;

    /**
     * @var EmployeeVerificationServiceInterface
     */
    private $verificationService;

    /**
     * EmployeeService constructor.
     * @param EmployeeRepositoryInterface $employeeRepository
     * @param EmployeeVerificationRepositoryInterface $verificationRepository
     * @param EmployeeVerificationServiceInterface $verificationService
     */
    public function __construct(
        EmployeeRepositoryInterface $employeeRepository,
        EmployeeVerificationRepositoryInterface $verificationRepository,
        EmployeeVerificationServiceInterface $verificationService
    )
    {
        $this->dm = App::make(DocumentManager::class);
        $this->repository = $employeeRepository;
        $this->verificationRepository = $verificationRepository;
        $this->verificationService = $verificationService;
    }

    /**
     * Register new employee
     *
     * @param string $verificationId
     * @param App\Domains\Employee\ValueObjects\EmployeeProfile $profile
     * @param string $password
     * @return Employee
     * @throws App\Domains\Employee\Exceptions\ContactsNotVerified
     * @throws App\Domains\Employee\Exceptions\EmployeeAlreadyExists
     */
    public function register(
        string $verificationId,
        EmployeeProfile $profile,
        string $password
    ) : Employee
    {
        /** @var EmployeeVerification $verification */
        $verification = $this->verificationService->getRepository()->find($verificationId);
        if (!$verification) {
            throw new ContactsNotVerified(trans('exceptions.contacts.not_verified'));
        }
        if ($this->findByCompanyIdAndEmail($verification->getCompany()->getId(), $verification->getEmail())) {
            throw new EmployeeAlreadyExists(
                trans('exceptions.employee.already_exists', [
                    'email' => $verification->getEmail(),
                    'company' => $verification->getCompany()->getProfile()->getName(),
                ])
            );
        }
        $employee = Employee::register($verification, $profile, $password);
        $this->dm->persist($employee);
        $this->dm->persist($verification->getCompany());
        $this->dm->flush();

        return $employee;
    }

    /**
     * Get colleagues of an employee specified
     *
     * @param Employee $employee
     * @return array
     */
    public function getColleagues(Employee $employee)
    {
        $invitations = [];
        $invitationsCursor = $this->verificationRepository->getVerificationsByEmployee($employee);
        foreach ($invitationsCursor as $invite) {
            $invitations[] = $invite;
        }
//        $invitations = $this->verificationRepository->findBy([
//            'reason' => EmployeeVerification::REASON_INVITED_BY_EMPLOYEE,
//            'emailVerified' => false,
//        ]);
        $active = $employee->getCompany()
            ->getEmployees()
            ->filter(function (Employee $empl) use ($employee) {
                return $empl->getId() !== $employee->getId() && $empl->isActive();
            })->toArray();
        $deleted = $employee->getCompany()
            ->getEmployees()
            ->filter(function (Employee $empl) use ($employee) {
                return $empl->getId() !== $employee->getId() && !$empl->isActive();
            })->toArray();
        return [
            'self' => $employee,
            'active' => $active,
            'deleted' => $deleted,
            'invitations' => $invitations,
        ];
    }

    /**
     * @param string $id
     * @return Employee
     */
    public function findById(string $id) : Employee
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $ids
     * @return Collection
     */
    public function findByMatrixIds(array $ids) : Collection
    {
        $employees = $this->repository->findAllByMatrixIds($ids);
        $collection = [];
        foreach ($employees as $employee) {
            $collection[] = $employee;
        }

        return Collection::make($collection);
    }

    /**
     * @param string $email
     * @return Collection
     */
    public function findByEmail(string $email) : Collection
    {
        $employees = $this->repository->findBy([
            'contacts.email' => $email,
        ]);

        return new Collection($employees);
    }

    /**
     * Find employee by login
     * @param string $login
     * @return mixed
     */
    public function findByLogin(string $login)
    {
        $employee = new Collection($this->repository->findBy([
            'profile.login' => $login,
        ]));

        return $employee->first();
    }

    /**
     * @param string $id
     * @param string $email
     * @return \App\Domains\Employee\Entities\Employee|null
     */
    public function findByCompanyIdAndEmail(string $id, string $email)
    {
        /** @var Company $company */
        $company = $this->dm->getRepository(Company::class)->find($id);
        if (!$company) {
            throw new CompanyNotFound(trans('exceptions.company.not_found'));
        }

        return $company->getEmployees()->filter(function (Employee $employee) use($email) {
            return $employee->getContacts()->getEmail() === $email;
        })->first();
    }

    /**
     * @param string $email
     * @param string $password
     * @return Collection
     */
    public function findByEmailAndPassword(string $email, string $password) : Collection
    {
        $employees = $this->findByEmail($email);
        return $employees->filter(function (Employee $employee) use($password) {
            return $employee->checkPassword($password);
        });
    }

    /**
     * Find all employee instances that matches to the verified email address
     * This method is used to get companies for selecting during password restore process. We want to
     * protect user's private personal data(list of companies he is registered in for example)
     * and verification process is used to protect such kind of data using PIN code.
     * For now we just use email protection but it seems like we will use SMS protection in the future.
     *
     * @param string $verificationId
     * @return Collection
     * @throws \App\Domains\Employee\Exceptions\EmployeeVerificationException
     * @throws EmployeeVerificationNotFound
     */
    public function findByVerificationId(string $verificationId) : Collection
    {
        /** @var EmployeeVerification $verificationProcess */
        $verificationProcess = $this->verificationRepository->find($verificationId);
        if (!$verificationProcess instanceof EmployeeVerification) {
            throw new EmployeeVerificationNotFound('Employee Verification Entity id: ' . $verificationId . ' Not found');
        }
        if (!$verificationProcess->isEmailVerified()) { // We cannot use this verification process if email is not verified
            throw new EmployeeVerificationException("Employee didn't verify email");
        }

        return $this->findByEmail($verificationProcess->getEmail());
    }


    /**
     * @param array $options
     * @return Collection
     */
    public function getMatchingCompanies(array $options) : Collection
    {
        return $this->getEmployeesCompanies($this->getEmployeeByOptions($options));
    }

    /**
     * @param Collection $employees
     * @return Collection
     */
    public function getEmployeesCompanies(Collection $employees) : Collection
    {
        $companies = new Collection();
        $employees->each(function (Employee $employee) use ($companies) {
            $companies->put($employee->getCompany()->getId(), $employee->getCompany());
        });
        return $companies;
    }

    /**
     * @param string $verificationId
     * @param string $companyId
     * @return Employee
     */
    public function matchVerificationAndCompany(string $verificationId, string $companyId) : Employee
    {
        /** @var Company|null $company */
        $company = $this->dm->getRepository(Company::class)->find($companyId);
        /** @var EmployeeVerification $verification */
        $verification = $this->dm->getRepository(EmployeeVerification::class)->find($verificationId);
        if (!$verification) {
            throw new HttpException(401, trans('exceptions.verification.failed'));
        }

        $employee = $this->repository->findByDepartmentAndEmail($company->getRootDepartment(), $verification->getEmail());
        if (!$employee) {
            throw new HttpException(401, trans('exceptions.verification.failed'));
        }
        return $employee->getNext();
    }


    /**
     * Change password of an employee
     *
     * @param Employee $employee
     * @param string $newPassword
     * @param null $oldPassword
     * @return Employee
     * @throws PasswordMismatchException
     */
    public function changePassword(Employee $employee, string $newPassword, $oldPassword = null)
    {
        if ($oldPassword && !$employee->checkPassword($oldPassword)) {
            throw new PasswordMismatchException(trans('exceptions.change-password.mismatch'));
        }
        $employee->changePassword($newPassword);
        $this->dm->persist($employee);
        $this->dm->flush();
        return $employee;
    }


    /**
     * @param Employee $admin
     * @param string $id
     * @return Employee
     *
     * @throws App\Domains\Employee\Exceptions\PermissionDenied
     */
    public function deactivate(Employee $admin, string $id)
    {
        /** @var Employee $employee */
        $employee = $this->repository->find($id);
        if (!$employee) {
            throw new App\Domains\Employee\Exceptions\EmployeeNotFound(trans('exceptions.employee.not_found'));
        }
        if ($employee->getCompany()->getId() !== $admin->getCompany()->getId()) {
            throw new PermissionDenied(trans('exceptions.employee.access_denied'));
        }
        $employee->deactivate();
        $this->dm->persist($employee);
        $this->dm->flush();
        return $employee;
    }

    /**
     * Send invitation to the company to an email and associate verification process with company and email
     * @param string $email
     * @param Employee $inviter
     * @return EmployeeVerification
     * @throws EmployeeVerificationAlreadySent
     * @throws InvitationLimitReached
     */
    public function invite(string $email, Employee $inviter) : EmployeeVerification
    {
        if ($this->invitationLimitReached($inviter->getCompany(), $email)) {
            throw new InvitationLimitReached(
                trans('exceptions.invitation.limitReached',
                ['email' => $email, 'limit' => config('mail.invitations.max_company_user')]
            ));
        }
        $validator = Validator::make(
            ['value' => $email],
            ['value' => 'email']
        );
        $validator->validate();
        if ($this->repository->findByCompanyAndEmail($inviter->getCompany(), $email)) {
            throw new EmployeeVerificationAlreadySent(trans('exceptions.invitation.alreadyExists', ['email' => $email]));
        }
        $employeeVerification = new EmployeeVerification(EmployeeVerification::REASON_INVITED_BY_EMPLOYEE);
        $employeeVerification->associateEmail($email);
        $employeeVerification->associateCompany($inviter->getCompany());
        $this->dm->persist($employeeVerification);
        Mail::to($email)->queue(
            new InviteColleague(
                $inviter->getProfile()->getName(),
                $email,
                $employeeVerification->getId(),
                $employeeVerification->getCompany()->getProfile()->getName(),
                $employeeVerification->getEmailCode()
            )
        );
        return $employeeVerification;
    }


    /**
     * Invite by many emails. Push emailing tasks to queue, generate verification instances,
     * associate email and encode code in email url
     *
     * @param Collection $invitees
     * @param $inviter Employee
     * @return Collection
     * @throws \App\Domains\Employee\Exceptions\EmployeeAlreadyExists
     */
    public function inviteMany(Collection $invitees, Employee $inviter)
    {
        $verifications = new Collection();
        $errors = new Collection();
        $invitees->each(function(string $email) use ($inviter, $errors, $verifications) {
            try {
                $verifications->push($this->invite($email, $inviter));
            } catch (\Exception $exception) {
                $errors->push([
                    'status' => self::VERIFICATION_ERR,
                    'email' => $email,
                    'message' => $exception->getMessage()
                ]);
            }
        });
        $this->dm->flush();
        return new Collection([
            'results' => $verifications,
            'errors' => $errors,
        ]);
    }

    public function updateEmployee(Employee $employee, array $data)
    {
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'avatar':
                    $this->uploadAvatar($employee, $value);
                    break;
                case 'firstName':
                    $employee->getProfile()->changeFirstName($value);
                    break;
                case 'lastName':
                    $employee->getProfile()->changeLastName($value);
                    break;
                case 'position':
                    $employee->getProfile()->changePosition($value);
                    break;
            }
        }
        $this->dm->persist($employee);
        $this->dm->flush();
        return $employee;
    }


    /**
     * @param Employee $admin
     * @param string $id
     * @param bool $value
     * @return Employee
     * @throws App\Domains\Employee\Exceptions\EmployeeNotFound
     * @throws PermissionDenied
     */
    public function makeAdmin(Employee $admin, string $id, bool $value)
    {
        /** @var Employee $employee */
        $employee = $this->repository->find($id);
        if (!$employee) {
            throw new App\Domains\Employee\Exceptions\EmployeeNotFound(trans('exceptions.employee.not_found'));
        }
        if ($employee->getCompany()->getId() !== $admin->getCompany()->getId()) {
            throw new PermissionDenied(trans('exceptions.employee.access_denied'));
        }
        if (!$employee) {
            throw new App\Domains\Employee\Exceptions\EmployeeNotFound(trans('exceptions.employee.not_found'));
        }
        if ($value === true) {
            $employee->setScope($employee->getCompany(), EmployeeRole::ADMIN);
        } else {
            $employee->setScope($employee->getCompany(), EmployeeRole::EMPLOYEE);
        }
        $this->dm->persist($employee);
        $this->dm->flush();
        return $employee;
    }

    /**
     * @param $email
     * @param $companyId
     * @throws EmployeeNotFound
     */
    public function addContact($email, $companyId)
    {
        $contact = $this->findByCompanyIdAndEmail($companyId, $email);
        if (!$contact) {
            throw new EmployeeNotFound(trans('exceptions.employee.not_found', [
                'email' => $email,
            ]));
        }

        /**
         * @var $employee Employee
         */
        $employee = App::make('AppUser');
        $employee->addContact($contact);
        $this->dm->persist($employee);
        $this->dm->flush();
    }

    /**
     * @param string $id
     * @throws EmployeeNotFound
     */
    public function deleteContact(string $id)
    {
        /**
         * @var $contact Employee
         */
        $contact = $this->repository->find($id);
        if (!$contact) {
            throw new EmployeeNotFound(trans('exceptions.employee.not_found_id', [
                'id' => $id,
            ]));
        }

        /**
         * @var $employee Employee
         */
        $employee = App::make('AppUser');
        $employee->deleteContact($contact);
        $this->dm->persist($employee);
        $this->dm->flush();
    }

    /**
     * @return ArrayCollection
     */
    public function getContactList()
    {
        /**
         * @var $employee Employee
         */
        $employee = App::make('AppUser');
        return $employee->getContactList();
    }

    /**
     * TODO: async it! i.e raise event
     *
     * @param Employee $employee
     * @param string $data
     * @return string
     */
    private function uploadAvatar(Employee $employee, string $data)
    {
        $filepath = $employee->getCompany()->getId() . '/employees/avatars/' . uniqid('ava_') . '.png';
        if (empty($data) || is_null($data)) {
            $employee->getProfile()->unsetAvatar();
        } else {
            $employee->getProfile()->setAvatar(App::make(ImageService::class)->upload($filepath, $data));
        }
        $this->dm->persist($employee);
        return $employee->getProfile()->getAvatar();
    }


    /**
     * Check if invitation limit were reached
     * TODO: move this counter to redis
     * @param Company $company
     * @param string $email
     * @return bool
     */
    private function invitationLimitReached(Company $company, string $email)
    {
        $openVerificationsCount = $this->verificationRepository->getOpenVerificationsCountByCompanyAndEmail($company, $email);

        return $openVerificationsCount >= config('mail.invitations.max_company_user');
    }


    /**
     * Get employee entity by array options
     *
     * @param array $options
     * @return Collection
     */
    private function getEmployeeByOptions(array $options)
    {
        try {
            App::make('AppUser');
            return Collection::make([App::make('AppUser')]);
        } catch (\Exception $exception) {}
        if (array_key_exists('verificationId', $options) && $options['verificationId']) {
            return $this->findByVerificationId($options['verificationId']);
        }
        if (!array_key_exists('password', $options) || !$options['password']) {
            throw new UnauthorizedHttpException(trans('auth.exceptions.matching-companies-unauthorized'));
        }
        if (!array_key_exists('email', $options) && !$options['email']) {
            throw new UnauthorizedHttpException(trans('auth.exceptions.matching-companies-unauthorized'));
        }

        return $this->findByEmailAndPassword($options['email'], $options['password']);
    }
}
