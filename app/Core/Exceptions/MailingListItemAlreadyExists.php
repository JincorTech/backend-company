<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 17:22
 */

namespace App\Core\Exceptions;
use Dingo\Api\Exception\ValidationHttpException;

class MailingListItemAlreadyExists extends ValidationHttpException
{

}
