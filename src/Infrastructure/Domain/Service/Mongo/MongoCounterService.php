<?php
namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Domain\Service\Mongo;

use MongoDB\Database;

class MongoCounterService
{
    /**
     *
     * @var \MongoDB\Collection
     */
    protected $seqCollection;

    /**
     *
     * @var string Sequence name
     */
    private $sequenceName;

    /**
     *
     * @var \MongoDB\Database
     */
    protected $database;

    public function __construct(Database $database, $sequenceName)
    {
        $this->database       = $database;
        $this->seqCollection = $database->seq;
        $this->sequenceName  = $sequenceName;
    }


    public function generateNewId()
    {
        if ($this->seqCollection->count(array('_id' => $this->sequenceName)) == 0) {
            $this->seqCollection->insertOne(array('_id' => $this->sequenceName, 'seq' => 1));
        }
        $seq = $this->seqCollection->findOneAndUpdate(
            array('_id' => $this->sequenceName),
            array('$inc' => array("seq" => 1)),
            array(
                "new" => true,
            )
        );
        return $seq['seq'];
    }
}