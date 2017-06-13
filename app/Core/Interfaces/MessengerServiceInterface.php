<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 14/06/2017
 * Time: 04:01
 */

namespace App\Core\Interfaces;


interface MessengerServiceInterface
{

    /**
     * Stores user auth data at auth service
     * @param array $data
     * @return bool
     */
    public function register(array $data);

}