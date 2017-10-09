<?php


namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Domain\Service;


interface ArraySerializerService
{
    /**
     * Transform array into object
     */
    public function fromArray(array $data);

    /**
     * Transform objec into array
     * @param $data
     * @return Object
     */
    public function toArray($data);

    /**
     * Return translated property by Serializer for a class
     * @param string $className
     * @param string $propertyName
     */
    public function getSerializedPropertyNameForClass($className, $propertyName);

    /**
     * Return translated property by Serializer for current class
     * @param string $propertyName
     */
    public function getSerializedPropertyName($propertyName);

}