<?php
namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Domain\Service\JMS\Entity\test\unit;


class Family
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var Person
     */
    private $father;
    /**
     * @var Person
     */
    private $mother;
    /**
     * @var Person[]
     */
    private $children;

    /**
     * Family constructor.
     */
    public function __construct()
    {
        $this->children = [];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Person
     */
    public function getFather()
    {
        return $this->father;
    }

    /**
     * @param Person $father
     * @return $this
     */
    public function setFather(Person $father)
    {
        $this->father = $father;
        return $this;
    }

    /**
     * @return Person
     */
    public function getMother()
    {
        return $this->mother;
    }

    /**
     * @param Person $mother
     * @return $this
     */
    public function setMother(Person $mother)
    {
        $this->mother = $mother;
        return $this;
    }

    /**
     * @return Person[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Person $children
     * @return $this
     */
    public function setChildren(array $children)
    {
        $this->children = $children;
        return $this;
    }

}