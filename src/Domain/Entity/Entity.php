<?php
namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Domain\Entity;

interface Entity
{

    /**
     * @param $id
     * @return Entity
     */
    function setId($id);

    /**
     * @return int id
     */
    function getId();
    
}