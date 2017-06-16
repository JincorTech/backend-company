<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/19/17
 * Time: 8:36 PM
 */

namespace App\Domains\Company\Services;

use Illuminate\Support\Collection;
use App\Domains\Employee\Services\EmployeeVerificationService;
use App\Domains\Company\Entities\EconomicalActivityType;
use App\Domains\Company\ValueObjects\CompanyExternalLink;
use App\Domains\Company\Search\CompanyIndexContract;
use App\Domains\Company\Events\CompanyUpdated;
use App\Domains\Company\Events\CompanyAdded;
use App\Domains\Company\Entities\CompanyType;
use App\Domains\Employee\Entities\Employee;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Company\Entities\Company;
use App\Core\Dictionary\Entities\Country;
use App\Core\Dictionary\Entities\City;
use App\Core\Services\AddressService;
use App\Core\ValueObjects\Address;
use Elasticsearch;
use App;
use Dingo\Api\Exception\ValidationHttpException;

class CompanyService implements CompanyIndexContract
{
    /**
     * @var DocumentManager|mixed
     */
    private $dm;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentRepository
     */
    public $repository;

    /**
     * @var \Doctrine\ODM\MongoDB\DocumentRepository
     */
    private $typesRepository;

    /**
     * @var App\Domains\Company\Repositories\EconomicalActivityRepository
     */
    private $eActivityRepository;

    /**
     * @var int
     */
    private $searchSize;

    public function __construct()
    {
        $this->dm = App::make(DocumentManager::class);
        $this->repository = $this->dm->getRepository(Company::class);
        $this->typesRepository = $this->dm->getRepository(CompanyType::class);
        $this->eActivityRepository = $this->dm->getRepository(EconomicalActivityType::class);
        $this->searchSize = config('elasticsearch.size') ?:  1000;
    }

    /**
     * Register new company
     *
     * @param string $country
     * @param string $legalName
     * @param string $companyType
     * @return App\Domains\Employee\Entities\EmployeeVerification
     */
    public function register(
        string $country,
        string $legalName,
        string $companyType
    ) {
        try {
            $address = $this->address()->build($country);
        } catch (\InvalidArgumentException $e) {
            $message = trans('registration.countryNotFound', [
                'country' => $country,
            ]);

            throw new ValidationHttpException([
                'country' => $message,
            ]);
        }

        $ct = $this->dm->getRepository(CompanyType::class)->find($companyType);
        if (!$ct) {
            $message = trans('registration.typeNotFound', [
                'ct' => $companyType,
            ]);

            throw new ValidationHttpException([
                'companyType' => $message,
            ]);
        }

        $company = new Company($legalName, $address, $ct);
        $this->dm->persist($company);
        event(new CompanyAdded($company));
        return $this->verification()->beginVerificationProcess($company);
    }

    /**
     * @param array $data
     * @param Company $company
     * @return Company
     */
    public function update(Company $company, array $data)
    {
        foreach ($data as $key => $value) {
                switch ($key) {
                    case 'brandName':
                        $company->getProfile()->setBrandNames($value);
                        break;

                    case 'links':
                       $this->updateLinks($company, $value);
                        break;

                    case 'address':
                        $this->updateAddress($company, $value);
                        break;

                    case 'phone':
                        $company->getProfile()->setPhone($value);
                        break;

                    case 'email':
                        $company->getProfile()->setEmail($value);
                        break;

                    case 'economicalActivityTypes':
                        $this->updateEATypes($company, $value);
                        break;

                    case 'companyType':
                        $type = $this->typesRepository->find($value);
                        if (!$type)
                            throw new UnprocessableEntityHttpException(trans('exceptions.company_type.not_found'));
                        $company->getProfile()->setCompanyType($type);
                        break;

                    case 'picture':
                        $this->uploadImage($company, $value);
                        break;

                    case 'legalName':
                        $company->getProfile()->changeName($value);
                        break;

                    case 'description':
                        $company->getProfile()->setDescription($value);
                        break;
                }

        }
        event(new CompanyUpdated($company));
        $this->dm->persist($company);
        $this->dm->flush();
        return $company;

    }

    public function uploadImage(Company $company, $data)
    {
        $filepath = $company->getId() . '/avatars/' . uniqid('pic_') . '.png';
        if (empty($data) || is_null($data)) {
            $company->getProfile()->unsetPicture();
        } else {
            $company->getProfile()->setPicture(App::make(App\Core\Services\ImageService::class)->upload($filepath, $data));
        }
        return $company->getProfile()->getPicture();
    }

    /**
     * @param Company $company
     * @param array $value
     */
    private function updateAddress(Company $company, array $value)
    {
        if (array_key_exists('country', $value) && !empty($value['country'])) {
            $country = $this->dm->getRepository(Country::class)->find($value['country']);
            if (!$country) {
                throw new UnprocessableEntityHttpException(trans('exceptions.country.not_found'));
            }
        } else {
            $country = $company->getProfile()->getAddress()->getCountry();
        }
        if (array_key_exists('city', $value) && !empty($value['city'])) {
            $city = $this->dm->getRepository(City::class)->find($value['city']);
            if (!$city) {
                throw new UnprocessableEntityHttpException(trans('exceptions.city.not_found'));
            }
        } else {
            $city = $company->getProfile()->getAddress()->getCity();
        }
        if (array_key_exists('formattedAddress', $value) && !empty($value['formattedAddress'])) {
            $formattedAddress = $value['formattedAddress'];
        } else {
            $formattedAddress = $company->getProfile()->getAddress()->getFormattedAddress();
        }
        $address = new Address($formattedAddress, $country, $city);
        $company->getProfile()->changeAddress($address);
    }

    private function updateLinks(Company $company, $value)
    {
        $links = [];
        foreach ($value as $linkKey => $link) {
            array_push($links, new CompanyExternalLink($link['value']));
        }
        $company->getProfile()->setLinks($links);
    }

    private function updateEATypes(Company $company, $value)
    {
        $types = [];
        foreach ($value as $eaKey => $ea) {
            $type = $this->eActivityRepository->find($ea);
            if (!$type)
                throw new UnprocessableEntityHttpException(trans('exceptions.economical_activity_type.not_found'));
            array_push($types, $type);
        }
        $company->getProfile()->setEconomicalActivities($types);
    }

    /**
     * @param null|string $query
     * @param null|string $country
     * @param null|string $activity
     * @return Collection
     */
    public function search($query = null, $country = null, $activity = null)
    {
        $result = Elasticsearch::search(
            $this->buildSearchRequest($query, $country, $activity)
        );

        if (!array_key_exists('hits', $result) || !array_key_exists('hits', $result['hits'])) {
            return new Collection();
        }
        $ids = Collection::make($result['hits']['hits'])->map(function($item) {
            return $item['_id'];
        });

        return Collection::make($this->repository->findBy([
            '_id' => [
                '$in' => $ids->toArray()
            ]
        ]));
    }


    /**
     * @param null|string $query
     * @param null|string $country
     * @param null|string $activity
     *
     * @return array
     */
    private function buildSearchRequest($query = null, $country = null, $activity = null)
    {
        $request = [
            'index' => '',
            'type' => '',
            '_source' => '_id',
            'size' => $this->searchSize,
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'bool' => [
                                'must' => []
                            ]
                        ]
                    ],
                ]
            ]
        ];

        if ($query !== null && !empty($query)) {
            $request['body']['query']['bool']['must'] = [
                'multi_match' => [
                    'query' => $query,
                    'fields'=> ['legalName', 'description', 'companyType*', 'economicalActivities*'],
                    'type' => 'cross_fields'
                ]
            ];
        }

        if ($country !== null) {
            $request['body']['query']['bool']['filter']['bool']['must'][] = [
                'term' => ['country' => $country],
            ];
        }

        if ($activity !== null) {
            $request['body']['query']['bool']['filter']['bool']['must'][] = [
                'term' => ['eActivityIds' => $activity],
            ];
        }

        return $request;
    }


    /**
     * @param string $id
     * @return null|Company
     */
    public function getCompany(string $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return array
     */
    public function getCompanyTypes()
    {
        return $this->typesRepository->findAll();
    }

    /**
     * @return array
     */
    public function getEconomicalActivityTypes()
    {
        return $this->eActivityRepository->findAll();
    }


    /**
     * @return array|mixed
     */
    public function getEARoot()
    {
        return $this->eActivityRepository->getRootNodes();
    }

    /**
     * TODO: replace with DI
     * @return AddressService
     */
    private function address()
    {
        return new AddressService();
    }

    /**
     * @TODO: replace with DI
     * @return EmployeeVerificationService
     */
    private function verification()
    {
        return new EmployeeVerificationService();
    }

}
