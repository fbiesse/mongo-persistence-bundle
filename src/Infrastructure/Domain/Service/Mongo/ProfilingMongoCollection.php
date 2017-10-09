<?php


namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Domain\Service\Mongo;


class ProfilingMongoCollection extends \MongoDB\Collection
{

    private $collection;
    private $collectionName;
    private $callsInfos;

    /**
     * ProfilingMongoCollection constructor.
     * @param $collection
     */
    public function __construct(\MongoDB\Driver\Manager $manager, $databaseName, $collectionName, array $calls)
    {
        $this->callsInfos = $calls;
        $this->collectionName = $collectionName;
        $this->collection = new \MongoDB\Collection($manager, $databaseName, $collectionName);
        parent::__construct($manager, $databaseName, $collectionName);
    }

    public function insertOne($a, array $options = array())
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function aggregate(array $pipeline, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function bulkWrite(array $operations, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function count($filter = [], array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function createIndex($key, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function createIndexes(array $indexes, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function deleteMany($filter, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function deleteOne($filter, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function distinct($fieldName, $filter = [], array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function drop(array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function dropIndex($indexName, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function dropIndexes(array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function find($filter = [], array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function findOne($filter = [], array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function findOneAndDelete($filter, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function findOneAndReplace($filter, $replacement, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function findOneAndUpdate($filter, $update, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function getCollectionName()
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function getDatabaseName()
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function getManager()
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function getNamespace()
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function insertMany(array $documents, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function listIndexes(array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function replaceOne($filter, $replacement, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function updateMany($filter, $update, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function updateOne($filter, $update, array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }

    public function withOptions(array $options = [])
    {
        return $this->call(__FUNCTION__, func_get_args());
    }


    public function call($pFuncName, array $pArgs)
    {
        $start = microtime(true);
        $toReturn = call_user_func_array(array($this->collection, $pFuncName), $pArgs);
        $time = microtime(true) - $start;
        $formattedArgs = $this->formatArg($pArgs);
        if (defined('JSON_PRETTY_PRINT')) {
            $arguments = json_encode($formattedArgs, JSON_PRETTY_PRINT);
        } else {
            $arguments = json_encode($formattedArgs);
        }

        $limit = 10;
        // limit does not exists from php 5.4
        $backtrace = @debug_backtrace(null, $limit);
        // offset is different in cli
        if(php_sapi_name() === 'cli'){
            array_shift($backtrace);
        }
        $this->callsInfos[] = array(
            'collection' => $this->collectionName,
            'funcname' => $pFuncName,
            'arguments' => $arguments,
            'time' => $time,
            'backtrace' => $this->getBackTrace($backtrace, $limit),
            'functionnamecaller' => $this->getFunctionNameCaller($backtrace),
        );
        return $toReturn;
    }

    private function getBackTrace($backtrace, $maxLevel = 100)
    {
        $data = [];
        $initIndex = 1;
        $maxIndex = $initIndex + $maxLevel;
        for($i = $initIndex; $i<$maxIndex && isset($backtrace[$i]) && isset($backtrace[$i]['file']); ++$i){
            $data[] = $backtrace[$i]['file'].' line '.$backtrace[$i]['line'];
        }
        return implode("\n", $data);
    }

    private function getLineCaller($backtrace)
    {
        return $backtrace[1]['line'];
    }

    private function getFunctionNameCaller($backtrace)
    {
        if (isset($backtrace[2])) {
            return $backtrace[2]['function'];
        }
    }

    private function formatArg($arg) {
        if(is_object($arg)) {
            $arg = (array)$arg;

        }
        if(is_array($arg)) {
            $new = array();
            foreach($arg as $key => $val) {
                $new[$key] = $this->formatArg($val);
            }
        }else {
            $new = $arg;
        }
        return $new;
    }
}