<?php


namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Domain\Service\JMS;


use FBiesse\Sf\Bundle\MongoPersistenceBundle\Domain\Service\ArraySerializerService;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;


class JMSArraySerializerService implements ArraySerializerService
{
    /** @var Serializer */
    private $serializer;

    /** @var string */
    private $type;

    /** @var bool */
    private $serializeNull;

    /**
     * @param Serializer $serializer
     * @param string     $type
     * @param bool       $serializeNull
     */
    public function __construct(Serializer $serializer, $type, $serializeNull = true)
    {
        $this->serializer    = $serializer;
        $this->type          = $type;
        $this->serializeNull = $serializeNull;
    }

    public function fromArray(array $data)
    {
        $json = json_encode($data);
        $object = $this->serializer->deserialize($json, $this->type, 'json');
        return $object;
    }

    public function toArray($data)
    {
        $json = $this->serializer->serialize($data, 'json', $this->getSerializationContext());
        return json_decode($json, true);
    }

    /**
     * @see \FBiesse\Sf2\Bundle\CapvitaBundle\Domain\Service\ArraySerializerService::getSerializedPropertyNameForClass()
     */
    public function getSerializedPropertyNameForClass($className, $propertyName)
    {
        $serializedName = null;
        $namingStrategy = $this->getNamingStrategy();
        $metadataFactory = $this->serializer->getMetadataFactory();
        $metadata = $metadataFactory->getMetadataForClass($className);
        $propertiesMetadata = $metadata->propertyMetadata;

        if(isset($propertiesMetadata[$propertyName])){
            $propertyMetadata = $propertiesMetadata[$propertyName];
            if($propertyMetadata->serializedName !== null){
                $serializedName = $propertiesMetadata[$propertyName]->serializedName;
            }else{
                $serializedName = $namingStrategy->translateName($propertyMetadata);
            }
        }else{
            throw new \Exception("Unknown property ".$propertyName.' for '.$className);
        }
        return $serializedName;
    }

    /**
     * @return \JMS\Serializer\Naming\PropertyNamingStrategyInterface
     */
    private function getNamingStrategy()
    {
        $rServializer = new \ReflectionObject($this->serializer);
        $pSerializationVisitors = $rServializer->getProperty('serializationVisitors');
        $pSerializationVisitors->setAccessible(true);
        $visitorMap = $pSerializationVisitors->getValue($this->serializer);
        $keys = $visitorMap->keys();
        $firstKey = reset($keys);
        $visitor = $visitorMap->get($firstKey)->get();
        return  $visitor->getNamingStrategy();
    }

    public function getSerializedPropertyName($propertyName)
    {
        return $this->getSerializedPropertyNameForClass($this->type, $propertyName);
    }

    private function getSerializationContext()
    {
        $context = new SerializationContext();

        if (true === $this->serializeNull) {
            // Force JmsSerializer to create null properties
            $context->setSerializeNull(true);
        }

        return $context;
    }
}