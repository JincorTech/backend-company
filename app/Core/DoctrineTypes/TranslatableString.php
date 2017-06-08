<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 12/04/2017
 * Time: 19:05
 */

namespace App\Core\DoctrineTypes;


use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Types\HashType;
use App\Core\ValueObjects\TranslatableString as TS;

class TranslatableString extends HashType
{

    public function convertToDatabaseValue($value)
    {
        if ($value !== null && !$value instanceof TS) {
            throw MongoDBException::invalidValueForType('TranslatableString', array('TranslatableString', 'null'), $value);
        }
        return $value !== null ? (object) $value->getValues() : null;
    }

    public function convertToPHPValue($value)
    {
        return $value !== null ? new TS((array) $value) : null;
    }


}