<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 31/03/2017
 * Time: 13:14
 */

namespace App\Core\Dictionary\Traits;


use App\Core\ValueObjects\TranslatableString;

trait HasTranslatableName
{

    /**
     * @var TranslatableString
     */
    protected $names;

    /**
     * @param string $locale
     * @return string
     */
    public function getName($locale = null) : string
    {
        return $this->names->getValue($locale);
    }

    /**
     * @return array
     */
    public function getNames() : array
    {
        return $this->names->getValues();
    }

    /**
     * @param array $names
     */
    public function setNames(array $names)
    {
        $this->names = new TranslatableString($names);
    }

}