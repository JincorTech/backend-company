<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 10:21 PM
 */

namespace App\Core\ValueObjects;

use App\Core\Interfaces\TranslatableContentInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use InvalidArgumentException;

/**
 * Class TranslatableString
 * @package App\Core\ValueObjects
 *
 * @ODM\EmbeddedDocument
 */
class TranslatableString implements TranslatableContentInterface
{

    /**
     * @var array
     *
     * @ODM\Field(type="hash")
     */
    protected $values;


    /**
     * TranslatableString constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $defaultLocale = config('app.locale');
        if (!array_key_exists($defaultLocale, $values)) {
            throw new InvalidArgumentException("Default locale" . $defaultLocale . " should always be presented at translatable strings");
        }
        $this->values = $values;
    }


    /**
     * Get value for the specified locale
     *
     * @param string $locale
     * @return mixed
     */
    public function getValue($locale = null) : string
    {
        if($locale === null) {
            $locale = config('app.locale');
        }
        if (array_key_exists($locale, $this->values)) {
            return $this->values[$locale];
        }
        return $this->values[config('app.locale')];
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setValue(string $key, $value)
    {
        $this->values[$key] = $value;
    }

}