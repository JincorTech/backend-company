<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/26/17
 * Time: 8:54 PM
 */

namespace App\Domains\Company\ValueObjects;


use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use InvalidArgumentException;

/**
 * Class CompanyExternalLink
 * @package App\Domains\Company\ValueObjects\Company
 *
 * @ODM\EmbeddedDocument
 */
class CompanyExternalLink
{

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    private $url;


    public function __construct(string $name, string $url)
    {
        if (!$this->validateURL($url)) {
            throw new InvalidArgumentException("URL: " . $url . " should be a valid URL string");
        }
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }


    private function validateURL(string $url) : bool
    {
        return preg_match('#((https?)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i', $url);
    }



}