<?php

/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 12/7/16
 * Time: 12:47 AM
 */
use Illuminate\Database\Seeder;
use App\Domains\Company\Entities\CompanyType;
use Doctrine\ODM\MongoDB\DocumentManager;

class CompanyTypeSeeder extends Seeder
{
    public function run()
    {
        $data = $this->getData();
        foreach ($data as $type) {
            $entity = new \App\Domains\Company\Entities\CompanyType($type['name'], $type['code']);
            $this->getDm()->persist($entity);
        }
        $this->getDm()->flush();
    }

    private function getData()
    {
        return [
            [
                'code' => 'BT1',
                'name' => [
                    'en' => 'Private Company',
                    'ru' => 'Частная компания',
                ],
            ],
            [
                'code' => 'BT2',
                'name' => [
                    'en' => 'Public Company',
                    'ru' => 'Публичная компания',
                ],
            ],
            [
                'code' => 'BT3',
                'name' => [
                    'en' => 'Sole Proprietorship',
                    'ru' => 'Индивидуальный предприниматель',
                ],
            ],
            [
                'code' => 'BT4',
                'name' => [
                    'en' => 'Nonprofit organization',
                    'ru' => 'Некоммерческая организация',
                ],
            ],
            [
                'code' => 'BT5',
                'name' => [
                    'en' => 'Goverment Organization',
                    'ru' => 'Государственное предприятие',
                ],
            ],
            [
                'code' => 'BT6',
                'name' => [
                    'en' => 'State-owned enterprise',
                    'ru' => 'Государственное предприятие',
                ],
            ],
            [
                'code' => 'BT7',
                'name' => [
                    'en' => 'International organization',
                    'ru' => 'Международная организация',
                ],
            ],
        ];
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    private function getDm()
    {
        return $this->container->make(DocumentManager::class);
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentRepository
     */
    private function getCurrencyRepository()
    {
        return $this->getDm()->getRepository(CompanyType::class);
    }
}
