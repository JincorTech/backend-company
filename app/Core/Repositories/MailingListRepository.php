<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.07.17
 * Time: 16:34
 */

namespace App\Core\Repositories;
use Doctrine\ODM\MongoDB\DocumentRepository;
use App\Core\ValueObjects\MailingListItem;
use App\Core\Interfaces\MailingListRepositoryInterface;

class MailingListRepository extends DocumentRepository implements MailingListRepositoryInterface
{
    public function findByEmailAndSubject(string $email, string $subject)
    {
        return $this->findOneBy([
            'email' => $email,
            'mailingListId' => MailingListItem::getMailingLists()[$subject],
        ]);
    }
}
