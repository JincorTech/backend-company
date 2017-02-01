<?php


use App\Domains\Company\Entities\Company;
use App\Domains\Company\Repositories\CompanyRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class CompanyCest
{
    /**
     * @var Company
     */
    private $company;

    /**
     * @var DocumentManager
     */
    private $dm;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    public function __construct()
    {
        $this->dm = App::make(DocumentManager::class);
        $this->companyRepository = $this->dm->getRepository(Company::class);

    }


    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }


    public function register(ApiTester $I)
    {
        $I->wantTo('Register new company in existing country and with existing company type.
         And get verification ID as a response');

        $I->sendGET('dictionary/country');
        $countriesResponse = $I->grabResponse();
        $countryId = json_decode($countriesResponse, true)['data'][0]['id'];

        $I->sendGET('company/types');
        $companyTypeResponse = $I->grabResponse();
        $companyType = json_decode($companyTypeResponse, true)['data'][0]['id'];

        $I->sendPOST('company', [
            'legalName' => 'Рога и Копыта',
            'countryId' => $countryId,
            'companyType' => $companyType,
        ]);

        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('data.id');
        $I->seeResponseJsonMatchesJsonPath('data.companyId');
        $I->seeResponseJsonMatchesJsonPath('data.email');
        $I->seeResponseJsonMatchesJsonPath('data.email.value');
        $I->seeResponseJsonMatchesJsonPath('data.email.isVerified');
        $I->seeResponseJsonMatchesJsonPath('data.phone.isVerified');
        $I->seeResponseJsonMatchesJsonPath('data.phone.value');

        $company = json_decode($I->grabResponse(), true)['data']['companyId'];
        $I->seeHttpHeader('Location', '/api/v1/company/' . $company);


        $I->sendPOST('company', [
        'legalName' => 'Рога и Копыта',
        'countryId' => $companyType,
        'companyType' => $companyType,
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();


        $I->sendPOST('company', [
            'legalName' => 'Рога и Копыта',
            'countryId' => $countryId,
            'companyType' => $countryId,
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();


        $I->sendPOST('company', [
            'legalName' => 'Рога и Копыта',
            'countryId' => $countryId,
            'companyType' => $companyType . 'd',
        ]);

        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();


        $I->sendPOST('company', [
            'legalName' => 'Рога и Копыта',
            'countryId' => $countryId . 'd',
            'companyType' => $companyType,
        ]);

        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();

        $this->company = $this->companyRepository->find($company);
    }



    public function update(ApiTester $I)
    {
        $I->wantTo('Update existing company');

        $economicalActivities = $this->company->getProfile()->getEconomicalActivities();

        $I->sendPUT('company', [
            'id' => $this->company->getId(),
            'legalName' => 'New legal name',
            'profile' => [
                'brandName' => [
                    'en' => 'New brand name',
                    'ru' => 'Новое брендовое название'
                ],
                'companyType' => $this->company->getProfile()->getType()->getId(),
                'economicalActivities' => [],
                'links' => [
                    'name' => null,
                    'value' => 'http://facebook.com/JinCor'
                ],
                'email' => 'company@email.com',
                'phone' => '+79506039921',
            ]
        ]);
    }



}
