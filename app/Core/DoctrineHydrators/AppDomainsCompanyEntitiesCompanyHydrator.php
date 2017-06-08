<?php

namespace App\Core\DoctrineHydrators;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\Query\Query;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class AppDomainsCompanyEntitiesCompanyHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data, array $hints = array())
    {
        $hydratedData = array();

        /** @Field(type="bin_uuid") */
        if (isset($data['_id']) || (! empty($this->class->fieldMappings['id']['nullable']) && array_key_exists('_id', $data))) {
            $value = $data['_id'];
            if ($value !== null) {
                $return = $value !== null ? ($value instanceof \MongoBinData ? $value->bin : $value) : null;
            } else {
                $return = null;
            }
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }

        /** @EmbedOne */
        if (isset($data['profile'])) {
            $embeddedDocument = $data['profile'];
            $className = $this->unitOfWork->getClassNameForAssociation($this->class->fieldMappings['profile'], $embeddedDocument);
            $embeddedMetadata = $this->dm->getClassMetadata($className);
            $return = $embeddedMetadata->newInstance();

            $this->unitOfWork->setParentAssociation($return, $this->class->fieldMappings['profile'], $document, 'profile');

            $embeddedData = $this->dm->getHydratorFactory()->hydrate($return, $embeddedDocument, $hints);
            $embeddedId = $embeddedMetadata->identifier && isset($embeddedData[$embeddedMetadata->identifier]) ? $embeddedData[$embeddedMetadata->identifier] : null;

            if (empty($hints[Query::HINT_READ_ONLY])) {
                $this->unitOfWork->registerManaged($return, $embeddedId, $embeddedData);
            }

            $this->class->reflFields['profile']->setValue($document, $return);
            $hydratedData['profile'] = $return;
        }

        /** @Many */
        $mongoData = isset($data['departments']) ? $data['departments'] : null;
        $return = $this->dm->getConfiguration()->getPersistentCollectionFactory()->create($this->dm, $this->class->fieldMappings['departments']);
        $return->setHints($hints);
        $return->setOwner($document, $this->class->fieldMappings['departments']);
        $return->setInitialized(false);
        if ($mongoData) {
            $return->setMongoData($mongoData);
        }
        $this->class->reflFields['departments']->setValue($document, $return);
        $hydratedData['departments'] = $return;
        return $hydratedData;
    }
}