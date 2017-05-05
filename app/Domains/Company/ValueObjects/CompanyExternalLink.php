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


    public function __construct(string $url)
    {
        if (!$this->validateURL($url)) {
            throw new InvalidArgumentException("URL: " . $url . " should be a valid URL string");
        }
        $this->name = $this->getDomain($url);
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

    public function getDomain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }



}