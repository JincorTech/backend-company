<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 30/11/2017
 * Time: 19:25
 */

namespace App\Core\Interfaces;


interface WalletsServiceInterface
{
    public function register(array $data, string $jwt);

    public function registerCorporate(array $data, string $jwt);
}