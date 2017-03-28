<?php

/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 10/20/16
 * Time: 10:29 PM
 */
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\EventManager;
use Gedmo\Tree\TreeListener;
use Gedmo\Timestampable\TimestampableListener;
use Gedmo\Sluggable\SluggableListener;
use Gedmo\Loggable\LoggableListener;
use Gedmo\Sortable\SortableListener;
use Gedmo\Translatable\TranslatableListener;

class DoctrineInit
{
    public static function createDocumentManager()
    {
        $config = new Configuration();
        $connection = new Connection(
            'mongodb://'
            .config('database.connections.mongodb.host')
            .':'
            .config('database.connections.mongodb.port')
        );
        $config->setProxyDir(app_path('Core/DoctrineProxies'));
        $config->setHydratorDir(app_path('Core/DoctrineHydrators'));
        $config->setProxyNamespace('App\Core\DoctrineProxies');
        $config->setHydratorNamespace('App\Core\DoctrineHydrators');
        $config->setDefaultDB('ubn-test');
        $config->setMetadataDriverImpl(AnnotationDriver::create(config('app.entityPaths')));
        AnnotationDriver::registerAnnotationClasses();

        $subscribers = [
            TreeListener::class,
            TimestampableListener::class,
            SluggableListener::class,
            LoggableListener::class,
            SortableListener::class,
            TranslatableListener::class,
        ];
        $evm = new EventManager();

        foreach ($subscribers as $subscriber) {
            $evm->addEventSubscriber(new $subscriber);
        }
        return DocumentManager::create($connection, $config, $evm);
    }
}
