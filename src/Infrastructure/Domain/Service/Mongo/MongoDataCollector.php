<?php


namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Domain\Service\Mongo;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class MongoDataCollector extends DataCollector
{
    private $mongoDb;

    public function __construct(ProfilingDatabase $mongoDb)
    {
        $this->mongoDb = $mongoDb;
        $this->data = array();
        $this->data['calls'] = array();
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['calls'] = $this->mongoDb->getCallsInfos();
    }

    public function getCalls()
    {
        return $this->data['calls'];
    }

    public function getTotalTime()
    {
        $total_time = 0;
        foreach ($this->data['calls'] as $info) {
            $total_time += $info['time'];
        }
        return $total_time;
    }

    public function getCount()
    {
        return count($this->data['calls']);
    }
    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     */
    public function getName()
    {
        return 'datacollector_mongodb';
    }
}