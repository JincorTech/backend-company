<?php

/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/24/17
 * Time: 4:37 PM
 */
use Illuminate\Database\Seeder;
use App\Domains\Company\Entities\EconomicalActivityType;

class CompanyActivityTypes extends Seeder
{
    /**
     * Run Seeder.
     */
    public function run()
    {
        $file = fopen('database/datasets/activities.csv', 'r');
        while (($line = fgetcsv($file)) !== false) {
            if (strlen($line[0]) === 1) {
                $this->makeParent($line);
            } else {
                $this->makeChild($line);
            }
        }
    }

    /**
     * Make parent node and store to DB.
     *
     * @param array $data
     * @return EconomicalActivityType
     */
    private function makeParent(array $data)
    {
        $parent = $this->buildInstance($data);
        $this->getDm()->persist($parent);
        $this->getDm()->flush($parent);

        return $parent;
    }

    /**
     * Make childNode and store to DB.
     *
     * @param array $data
     */
    private function makeChild(array $data)
    {
        /** @var EconomicalActivityType $parent */
        $parent = $this->findParent($data);
        $child = $this->buildInstance($data);
        $child->setParent($parent);
        $this->getDm()->persist($child);
        $this->getDm()->flush($child);
    }

    /**
     * Find parent node by the children data.
     *
     * @param array $data
     * @return object
     */
    private function findParent(array $data)
    {
        $parentCode = substr($data[0], 0, strlen($data[0]) - 1);

        return $this->getRepository()->findOneBy([
            'internalCode' => $parentCode,
        ]);
    }

    /**
     * Create an instance of Entity.
     *
     * @param array $data
     * @return EconomicalActivityType
     */
    private function buildInstance(array $data)
    {
        $names = $this->extractNames($data);

        return new EconomicalActivityType($names, $data[0]);
    }

    /**
     * Get names from data array.
     *
     * @param array $data
     * @return array
     */
    private function extractNames(array $data)
    {
        return [
            'ru' => trim((explode('/', $data[1]))[0]),
            'en' => trim((explode('/', $data[1]))[1]),
        ];
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    private function getDm()
    {
        return $this->container->make(\Doctrine\ODM\MongoDB\DocumentManager::class);
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentRepository
     */
    private function getRepository()
    {
        return $this->getDm()->getRepository(EconomicalActivityType::class);
    }
}
