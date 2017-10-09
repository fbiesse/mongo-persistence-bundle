<?php


namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Domain\Service\Mongo;


use FBiesse\Sf\Bundle\MongoPersistenceBundle\Domain\Exception\NotFoundException;
use FBiesse\Sf\Bundle\MongoPersistenceBundle\Domain\Service\ArraySerializerService;

abstract class AbstractMongoRepository
{

    /**
     * @var \MongoDb\Collection
     */
    protected $collection;
    /**
     * @var ArraySerializerService
     */
    protected $assembler;

    public function __construct(\MongoDb\Database $database, ArraySerializerService $assembler)
    {
        $this->collection = $database->{$this->getCollectionName()};
        $this->assembler = $assembler;
    }

    protected function fromMongoCursorToObjectArray(array $criteria, $offset = null, $limit = null, $sort = null)
    {
        $options = [];
        if ($offset !== null) {
            $options['skip'] = $offset;
        }
        if ($limit !== null) {
            $options['limit'] = $limit;
        }
        if ($sort !== null) {
            $options['sort'] = $sort;
        }
        $elements = $this->collection->find($criteria, $options);

        $results = [];
        foreach ($elements as $element) {
            $results[] = $this->assembler->fromArray((array)$element);
        }
        return $results;
    }

    protected function fromMongoCursorToObject(array $criteria)
    {
        $elements = $this->collection->find($criteria);
        $elements = iterator_to_array($elements);
        $nbElement = count($elements);
        if ($nbElement !== 1) {
            throw new NotFoundException(
                sprintf(
                    'Impossible to find one element for entity "%s" with params [%s], %s found',
                    $this->getCollectionName(),
                    json_encode($criteria),
                    (int)$nbElement
                )
            );
        }

        $element = reset($elements);
        return $this->assembler->fromArray((array)$element);
    }

    abstract protected function getCollectionName();
}
