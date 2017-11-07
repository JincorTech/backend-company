<?php

namespace App\Core\Interfaces;

interface IdentityInterface
{
    /**
     * Validates JWT token and returns decoded token data
     * @param string $token
     * @return \JincorTech\AuthClient\UserTokenVerificationResult
     */
    public function validateToken(string $token);

    /**
     * Stores user auth data at auth service
     * @param array $data
     * @return bool
     */
    public function register(array $data);

    /**
     * @param string $email
     * @param string $password
     * @param $company
     * @return bool|mixed
     */
    public function login(string $email, string $password, $company);

}