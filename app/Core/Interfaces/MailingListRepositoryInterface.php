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
    public function findByEmailAndMailingListId(string $email, string $id);
}
