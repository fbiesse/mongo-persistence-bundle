<?php


namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Domain\Service\Mongo;


use MongoDB\Client;

class ProfilingDatabase extends \MongoDB\Database
{
    private $collectionCache;

    private $databaseName;
    private $callsInfos;

    /**
     * GedClient constructor.
     * @param $mongoDb
     */
    public function __construct(Client $client, $databaseName)
    {
        parent::__construct($client->getManager(), $databaseName);
        $this->databaseName = $databaseName;
        $this->collectionCache = [];
        $this->callsInfos = [];
    }

    public function selectCollection($collectionName, array $options = Array())
    {
        if (!isset($this->collectionCache[$collectionName])) {
            $collection = new ProfilingMongoCollection($this->getManager(), $this->databaseName, $collectionName, $this->callsInfos);
            $this->collectionCache[$collectionName] = $collection;
        }
        return $this->collectionCache[$collectionName];
    }

    public function __get($name)
    {
        return $this->selectCollection($name);
    }


    /**
     * @return array
     */
    public function getCallsInfos()
    {
        return $this->callsInfos;
    }
}