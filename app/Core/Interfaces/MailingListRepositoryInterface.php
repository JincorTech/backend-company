<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 16:48
 */

namespace App\Core\Interfaces;


interface MailingListRepositoryInterface
{
    public function findByEmailAndSubject(string $email, string $subject);
}
