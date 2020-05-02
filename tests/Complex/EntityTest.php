<?php 

namespace Circuit\Tests\Complex;

use Circuit\Basic\StructureBuilder;
use Circuit\Complex;
use Circuit\Structured;
use Circuit\Interfaces;

/**
 * @coversDefaultClass Complex\ComplexEntity
 */
class EntityTest extends \PHPUnit\Framework\TestCase {

    //protected $builder = new \Circuit\Tests\Factory();

    public function setUp() :void {

    }

    /**
     * @covers ::__construct
     * @dataProvider providerConstruct
     */
    public function testConstruct($structure, $elementsCount, $connectionsCount) {
        $entity = new Complex\ComplexEntity($structure);
        $this->assertTrue($entity->getCore() instanceof Complex\NodeArray);
        $this->assertTrue($entity->getLimitation() instanceof Complex\EntryPointArray);
        $this->assertTrue($entity->getParticularity() instanceof Complex\EmptyFieldArray);
        $this->assertTrue($entity->getCore()->getIterator()->current() instanceof Interfaces\Element\Node);
        $this->assertTrue($entity->getLimitation()->getIterator()->current() instanceof Interfaces\Element\EntryPoint);
        $this->assertTrue($entity->getParticularity()->getIterator()->current() instanceof Interfaces\Element\EmptyField);
        $this->assertTrue(count($entity->getStructureElements()) == $elementsCount);
        $this->assertTrue(count($entity->getStructureConnections()) == $connectionsCount);
    }

    public function providerConstruct() {
        $builder = new \Circuit\Tests\Factory();
        return [
            'structuredEntity' => [$builder->createSimpleStructure(), 3, 2],
            'cycledStructure' => [$builder->createCycledStructure(), 4, 5],
            'fractalizedStructure' => [$builder->createFractalizedStructure(), 4, 5]
        ];
    }

    
}