<?php


namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Domain\Service\Mongo;


use FBiesse\Sf\Bundle\MongoPersistenceBundle\Domain\Entity\Entity;
use FBiesse\Sf\Bundle\MongoPersistenceBundle\Domain\Service\ArraySerializerService;

abstract class AbstractEntityMongoRepository extends AbstractMongoRepository
{
    /**
     * @var MongoCounterService
     */
    private $mongoCounterService;

    public function __construct(\MongoDb\Database $database, ArraySerializerService $assembler)
    {
        parent::__construct($database, $assembler);
        $this->mongoCounterService = new MongoCounterService($database, $this->getCollectionName());
    }

    /**
     * @param $id
     * @return Entity
     */
    public function get($id)
    {
        return $this->fromMongoCursorToObject(
            [
                $this->assembler->getSerializedPropertyName('id') => (int)$id
            ]
        );
    }

    public function insertEntity(Entity $entity)
    {
        $entity->setId($this->mongoCounterService->generateNewId());
        $this->collection->insertOne($this->assembler->toArray($entity));
        return $entity;
    }

    public function updateEntity(Entity $entity)
    {
        $id = $entity->getId();
        $this->collection->replaceOne(
            [
                $this->assembler->getSerializedPropertyName('id') => (int)$id
            ]
            , $this->assembler->toArray($entity)
        );
        return $this->get($id);
    }

}
