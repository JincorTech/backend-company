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
        $data = [];
        $file = fopen('database/datasets/BusinessTypes.csv', 'r');
        while (($line = fgetcsv($file)) !== false) {
            $type = [
                'code' => $line[0],
                'name' => [
                    'en' => $line[1],
                    'ru' => $line[2],
                ],
            ];
            $data[] = $type;
        }
        return $data;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    private function getDm()
    {
        return $this->container->make(DocumentManager::class);
    }

}
