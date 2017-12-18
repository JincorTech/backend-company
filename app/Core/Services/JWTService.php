<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 03/06/2017
 * Time: 21:49
 */

namespace App\Core\Services;


use App\Core\Interfaces\EmployeeVerificationReason;
use App\Domains\Company\Entities\Company;
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
     * @param string $companyName
     * @param string $companyId
     * @param string $reason
     * @return string
     */
    public function makeRegistrationToken(string $email, string $companyName, string $companyId, string $reason)
    {
        $token = [
            'iss' => config('url'),
            'aud' => config('url'),
            'iat' => Carbon::create()->getTimestamp(),
            'exp' => Carbon::create()->addWeeks(2)->getTimestamp(),
            'email' => $email,
            'companyName' => $companyName,
            'companyId' => $companyId,
            'reason' => $reason
        ];
        return JWT::encode($token, $this->key);
    }

    public function isValidRegistrationToken()
    {

    }

    /**
     * @param Company $company
     *
     * @return string
     */
    public function makeRegistrationCompanyToken(Company $company): string
    {
        $token = [
            'iss' => config('url'),
            'aud' => config('url'),
            'iat' => Carbon::create()->getTimestamp(),
            'exp' => Carbon::create()->addWeeks(2)->getTimestamp(),
            'companyName' => $company->getProfile()->getName(),
            'companyId' => $company->getId(),
            'reason' => EmployeeVerificationReason::REASON_REGISTER,
        ];
        return JWT::encode($token, $this->key);
    }

    public function getCompanyId(string $token): string
    {
        $data = JWT::decode($token, $this->key, ['HS256']);
        return $data->companyId;
    }

    public function getData(string $token): array
    {
        return (array) JWT::decode($token, $this->key, ['HS256']);
    }
}
