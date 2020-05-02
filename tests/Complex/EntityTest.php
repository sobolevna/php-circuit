<?php 

namespace Circuit\Tests\Complex;

use Circuit\Basic\StructureBuilder;
use Circuit\Complex;
use Circuit\Structured;
use Circuit\Interfaces;
use Circuit\Tests\Factory;

/**
 * @coversDefaultClass ComplexEntity
 */
class EntityTest extends \PHPUnit\Framework\TestCase {

    protected $class = Complex\Element::class;

    /**
     * @covers ::__construct
     * @dataProvider providerConstruct
     */
    public function testConstruct($structure, $elementsCount, $connectionsCount) {
        $entity = new $this->class($structure);
        $this->assertTrue($entity->getCore() instanceof Complex\NodeArray);
        $this->assertTrue($entity->getLimitation() instanceof Complex\EntryPointArray);
        $this->assertTrue($entity->getParticularity() instanceof Complex\EmptyFieldArray);
        $this->assertTrue($entity->getCore()->getIterator()->current() instanceof Interfaces\Node);
        $this->assertTrue($entity->getLimitation()->getIterator()->current() instanceof Interfaces\EntryPoint);
        $this->assertTrue($entity->getParticularity()->getIterator()->current() instanceof Interfaces\EmptyField);
        $this->assertTrue(count($entity->getStructureElements()) == $elementsCount);
        $this->assertTrue(count($entity->getStructureConnections()) == $connectionsCount);
    }

    public function providerConstruct() {
        $builder = new Factory();
        return [
            'structuredEntity' => [$builder->createSimpleStructure(), 3, 2],
            'cycledStructure' => [$builder->createCycledStructure(), 5, 6],
            'fractalizedStructure' => [$builder->createFractalizedStructure(), 5, 6]
        ];
    }

    
}