<?php 

namespace Circuit\Tests\Complex; 

use Circuit\{Interfaces, Basic, Structured, Complex};

class StructureTest extends \PHPUnit\Framework\TestCase {

    public function setUp() : void {
        $factory = new \Circuit\Factory();
        $builder = new Basic\StructureBuilder();
        $this->element = new Complex\Element($factory->createCycledStructure());
        $this->element->setDescription('First element');
        $this->elementToConnect = $factory->createSimpleComplexElement(Complex\Element::class);
        $this->elementToConnect->setDescription('Second element');
        $this->connection = $this->element->connect($this->elementToConnect);
    }

    public function testCreateStructure() {
        $builder = new Basic\StructureBuilder();
        $structure = $builder->setFirstElement($this->element)->build();
        $this->assertTrue(count($structure->getStructureElements()) == 2);
        $this->assertTrue(count($structure->getStructureConnections()) == 1);
        $this->assertTrue(\in_array($this->element, $structure->getStructureElements()));
        $this->assertTrue(\in_array($this->elementToConnect, $structure->getStructureElements()));
        $this->assertTrue(\in_array($this->connection, $structure->getStructureConnections()));
    }
    
}