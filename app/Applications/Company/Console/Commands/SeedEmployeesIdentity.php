<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 04/05/2017
 * Time: 12:50
 */

namespace App\Applications\Company\Console\Commands;

use Illuminate\Console\Command;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Employee\Entities\Employee;
use App\Core\Interfaces\IdentityInterface;
use App;

class SeedEmployeesIdentity extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'identity:seed:employees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed identity service with real employees data';

    public function handle()
    {
        /**
         * @var $identityService IdentityInterface
         */
        $identityService = App::make(IdentityInterface::class);
        $employees = App::make(DocumentManager::class)->getRepository(Employee::class)->findAll();
        foreach ($employees as $employee) {
            /**
             * @var $employee Employee
             */
            if ($employee->isActive()) {
                $data = [
                    'email' => $employee->getContacts()->getEmail(),
                    'password' => $employee->getPassword(),
                    'login' => $employee->getLogin(),
                    'companyName' => $employee->getCompany()->getProfile()->getName(),
                    'name' => $employee->getProfile()->getName(),
                    'position' => $employee->getProfile()->getPosition(),
                    'scope' => $employee->getProfile()->scope,
                    'sub' => $employee->getMatrixId(),
                ];
                $identityService->register($data);
            }
        }
    }
}
