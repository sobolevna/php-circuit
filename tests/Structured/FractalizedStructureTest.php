<?php 

namespace Circuit\Tests\Structured; 

use Circuit\Structured;
use Circuit\Basic\{Structure, StructureBuilder};

class FractalizedStructureTest extends StructureTest {

    public function setUp() : void {
        $this->node = $this->createElement(Structured\StructuredNode::class);
        $this->entryPoint = $this->createElement(Structured\StructuredEntryPoint::class);
        $this->emptyField = $this->createElement(Structured\StructuredEmptyField::class);
        $this->element = new Structured\StructuredElement($this->node, $this->entryPoint, $this->emptyField);
        $this->element->setDescription('First element');
        $this->elementToConnect = $this->createFractalizedElement();
        $this->elementToConnect->setDescription('Second element');
        $this->connection = $this->element->connect($this->elementToConnect);
    }

    public function testCreateCycledStructureFromBuilder() {
        $builder = new StructureBuilder();
        $newElement = $this->createFractalizedElement();
        $newElement->connect($this->element);
        $newElement->connect($this->elementToConnect);
        $newElement->setDescription('Third element');
        $additionalElement = $this->createFractalizedElement();
        $additionalElement->setDescription('Additional element out of cycle');
        $additionalElement->connect($newElement);
        $structure = $builder->setFirstElement($this->element)->build();
        $this->assertTrue(count($structure->getStructureElements()) == 4);
        $this->assertTrue(count($structure->getStructureConnections()) == 4);
        $this->assertTrue(\in_array($this->element, $structure->getStructureElements()));
        $this->assertTrue(\in_array($this->elementToConnect, $structure->getStructureElements()));
        $this->assertTrue(\in_array($newElement, $structure->getStructureElements()));
        $this->assertTrue(\in_array($this->connection, $structure->getStructureConnections()));
    }
    
    protected function createElement($class = Structured\StructuredElement::class) {
        $core = $this->createMock(\Circuit\Basic\Core::class);
        $limitation = $this->createMock(\Circuit\Basic\Limitation::class);
        $particularity = $this->createMock(\Circuit\Basic\Particularity::class);
        $node = new Structured\Node($core, $limitation, $particularity);
        $entryPoint = new Structured\EntryPoint($core, $limitation, $particularity);
        $emptyField = new Structured\EmptyField($core, $limitation, $particularity);
        return new $class($node, $entryPoint, $emptyField);
    }
    
    protected function createFractalizedElement($class = Structured\StructuredElement::class) {
        $node = $this->createElement(Structured\StructuredNode::class);
        $entryPoint = $this->createElement(Structured\StructuredEntryPoint::class);
        $emptyField = $this->createElement(Structured\StructuredEmptyField::class);
        return new $class($node, $entryPoint, $emptyField);
    }
}