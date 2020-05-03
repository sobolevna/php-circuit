<?php 

namespace Circuit\Tests\Complex;

use Circuit\Basic\StructureBuilder;
use Circuit\Complex;
use Circuit\Structured;
use Circuit\Interfaces;
use Circuit\Factory;

class ElementTest extends EntityTest {

    public function setUp() : void {
        $factory = new Factory();
        $this->element = new Complex\Element($factory->createCycledStructure());
    }

    protected $class = Complex\Element::class;

    /**
     * @covers ::__construct
     * @dataProvider providerConnect
     */
    public function testConnect($structure, $elementsCount, $connectionsCount) {
        $element = new $this->class($structure);
        $connection = $this->element->connect($element);
        $this->assertTrue(in_array($connection, $element->getConnections(), true));
        $this->assertTrue(in_array($element, $connection->getElements(), true));
        $this->assertTrue(in_array($element->getLimitation()->getIterator()->current(), $connection->getStructureElements(), true));
        $this->assertTrue(in_array($this->element->getLimitation()->getIterator()->current(), $connection->getStructureElements(), true));
        //var_dump($connection->getStructureElements());
        $this->assertCount($elementsCount, $connection->getStructureElements());
        $this->assertCount($connectionsCount, $connection->getStructureConnections());
    }

    public function providerConnect() {
        $builder = new Factory();
        return [
            'structuredEntity' => [$builder->createSimpleStructure(), 3, 2],
            'cycledStructure' => [$builder->createCycledStructure(), 4, 4],
            'fractalizedStructure' => [$builder->createFractalizedStructure(), 4, 4]
        ];
    }
}