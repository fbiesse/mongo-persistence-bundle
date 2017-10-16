<?php
namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Domain\Service\JMS\test\unit;

require_once __DIR__ . '/../JMS/../../../../../bootstrap.php';
require_once __DIR__ . '/Entity/Family.php';
require_once __DIR__ . '/Entity/Person.php';

use FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Domain\Service\JMS\Entity\test\unit\Family;
use FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Domain\Service\JMS\Entity\test\unit\Person;
use FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Domain\Service\JMS\JMSArraySerializerService as testedClass;

class JMSArraySerializerService extends \atoum\test
{
    private function getTestedClass()
    {
        $builder = \JMS\Serializer\SerializerBuilder::create();
        $builder->addMetadataDir(__DIR__ . '/Mapping', 'FBiesse\\Sf\\Bundle\\MongoPersistenceBundle\\Infrastructure\\Domain\\Service\\JMS\\Entity\\test\\unit');
        $serializer = $builder->build();
        return new testedClass($serializer, 'FBiesse\\Sf\\Bundle\\MongoPersistenceBundle\\Infrastructure\\Domain\\Service\\JMS\\Entity\\test\\unit\Family', true);
    }

    public function test_array_conversions()
    {
        $testedClass = $this->getTestedClass();

        $dad = new Person();
        $dad->setName('Simpson')->setFirstName('Homie')->setBirthDate(\DateTime::createFromFormat('Y-m-d\TH:i:sO', '1956-06-18T00:00:00+0100'));
        $mom = new Person();
        $mom->setName('Simpson')->setFirstName('Marge')->setBirthDate(\DateTime::createFromFormat('Y-m-d\TH:i:sO', '1959-06-29T00:00:00+0100'));
        $child1 = new Person();
        $child1->setName('Simpson')->setFirstName('Bart')->setBirthDate(\DateTime::createFromFormat('Y-m-d\TH:i:sO', '1981-04-1T00:00:00+0100'));
        $child2 = new Person();
        $child2->setName('Simpson')->setFirstName('Lisa')->setBirthDate(\DateTime::createFromFormat('Y-m-d\TH:i:sO', '1984-05-9T00:00:00+0100'));
        $child3 = new Person();
        $child3->setName('Simpson')->setFirstName('Maggie')->setBirthDate(\DateTime::createFromFormat('Y-m-d\TH:i:sO', '1988-11-7T00:00:00+0100'));

        $family = new Family();
        $family->setId(55)->setFather($dad)->setMother($mom)->setChildren([$child1, $child2, $child3]);

        $this
            ->assert('check toArray')
            ->if($arrayFamily = $testedClass->toArray($family))
            ->then(
                $this
                    ->array($arrayFamily)
                    ->isIdenticalTo(['_id' => 55,
                        'father' => [
                            'name' => 'Simpson',
                            'first_name' => 'Homie',
                            'birth_date' => '1956-06-18T00:00:00+0100',
                        ],
                        'mother' => [
                            'name' =>'Simpson',
                            'first_name' =>'Marge',
                            'birth_date' => '1959-06-29T00:00:00+0100'
                        ],
                        'children' =>[
                            [
                                'name' =>'Simpson',
                                'first_name' =>'Bart',
                                'birth_date' =>'1981-04-01T00:00:00+0100'
                            ],
                            [
                                'name' =>'Simpson',
                                'first_name' =>'Lisa',
                                'birth_date' =>'1984-05-09T00:00:00+0100'
                            ],
                            [
                                'name' =>'Simpson',
                                'first_name' =>'Maggie',
                               'birth_date' =>'1988-11-07T00:00:00+0100'
                            ]

                        ]
                    ])
            );
        $this
            ->assert('check fromArray')
            ->if($unserializedFamily = $testedClass->fromArray($arrayFamily))
                ->then(
                    $this
                        ->object($unserializedFamily)
                            ->isEqualTo($family)
                )
        ;

    }

}