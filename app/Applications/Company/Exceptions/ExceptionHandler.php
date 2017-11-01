<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 17/10/2017
 * Time: 20:21
 */

namespace App\Applications\Company\Exceptions;
use App\Applications\Company\Exceptions\Employee\EmployeeNotFound;
use App\Applications\Company\Exceptions\Employee\PermissionDenied;
use Dingo\Api\Routing\Helpers;
use Exception;


use App\Core\Exceptions\Handler;

class ExceptionHandler extends Handler
{

    use Helpers;

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //403
        if ($exception instanceof Employee\PermissionDenied) {
            $this->response->error($exception->getMessage(), 403);
        }
        //404
        if ($exception instanceof Employee\EmployeeNotFound) {
            $this->response->error($exception->getMessage(), 404);
        }
        //422
//        if ($exception instanceof ) {

//        }
        //500

        return parent::render($request, $exception);
    }

}