<?php 

namespace Circuit\Tests\Structured; 

use Circuit\{Basic, Structured};
use Circuit\Basic\{Structure, StructureBuilder};

class StructureTest extends \Circuit\Tests\Basic\StructureTest {

    public function setUp() : void {
        $core = $this->createMock(\Circuit\Basic\Core::class);
        $limitation = $this->createMock(\Circuit\Basic\Limitation::class);
        $particularity = $this->createMock(\Circuit\Basic\Particularity::class);
        $this->node = new Basic\Node($core, $limitation, $particularity);
        $this->entryPoint = new Basic\EntryPoint($core, $limitation, $particularity);
        $this->emptyField = new Basic\EmptyField($core, $limitation, $particularity);
        $this->element = new Structured\Element($this->node, $this->entryPoint, $this->emptyField);
        $this->element->setDescription('First element');
        $this->elementToConnect = $this->createElement();
        $this->elementToConnect->setDescription('Second element');
        $this->connection = $this->element->connect($this->elementToConnect);
    }

    /**
     * @covers Structured\Element::__construct
     */
    public function testCreateElement() {
        $this->assertTrue($this->element instanceof Structured\Entity);
        $this->assertTrue($this->element instanceof Structured\Element);
        $this->assertTrue(count($this->element->getStructureElements()) == 3);
        $this->assertTrue(\in_array($this->node, $this->element->getStructureElements(), true));
        $this->assertTrue(\in_array($this->entryPoint, $this->element->getStructureElements(), true));
        $this->assertTrue(\in_array($this->emptyField, $this->element->getStructureElements(), true));
        $this->assertTrue(count($this->element->getStructureConnections()) == 2);
    }

    /**
     * @covers Structured\Connection::__construct
     * @covers Structured\Element::connect
     * @covers Structured\Element::addConnection
     * @covers Structured\Element::getConnections
     * @covers Structured\Connection::getElements
     */
    public function testCreateConnection() {
        parent::testCreateConnection();
        $this->assertCount(2, $this->connection->getStructureElements());
        $this->assertCount(1, $this->connection->getStructureConnections());
    }
    
    protected function createElement() {
        $core = $this->createMock(\Circuit\Basic\Core::class);
        $limitation = $this->createMock(\Circuit\Basic\Limitation::class);
        $particularity = $this->createMock(\Circuit\Basic\Particularity::class);
        $node = new Basic\Node($core, $limitation, $particularity);
        $entryPoint = new Basic\EntryPoint($core, $limitation, $particularity);
        $emptyField = new Basic\EmptyField($core, $limitation, $particularity);
        return new Structured\Element($node, $entryPoint, $emptyField);
    }

    
}