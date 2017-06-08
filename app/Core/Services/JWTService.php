<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 03/06/2017
 * Time: 21:49
 */

namespace App\Core\Services;


use Carbon\Carbon;
use \Firebase\JWT\JWT;


class JWTService
{

    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @param string $email
     * @param string $verificationId
     * @param string $companyName
     * @param string $pin
     *
     * @return string
     */
    public function makeRegistrationToken(string $email, string $verificationId, string $companyName, string $pin)
    {
        $token = [
            'iss' => config('url'),
            'aud' => config('url'),
            'iat' => Carbon::create()->getTimestamp(),
            'exp' => Carbon::create()->addWeeks(2)->getTimestamp(),
            'email' => $email,
            'verificationId' => $verificationId,
            'companyName' => $companyName,
            'pin' => $pin,
        ];
        return JWT::encode($token, $this->key);
    }

    public function isValidRegistrationToken()
    {

    }

}