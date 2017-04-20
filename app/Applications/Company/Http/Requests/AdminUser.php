<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 19/04/2017
 * Time: 19:41
 */

namespace App\Applications\Company\Http\Requests;


use App\Domains\Employee\Entities\Employee;

trait AdminUser
{

    use AuthenticatedUser;

    /**
     * @return bool
     */
    public function authorize()
    {
        return $this->getUser() instanceof Employee && $this->getUser()->isAdmin();
    }


}