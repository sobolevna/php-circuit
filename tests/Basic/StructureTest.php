<?php 

namespace Circuit\Tests\Basic; 

use Circuit\Basic;

class StructureTest extends \PHPUnit\Framework\TestCase {

    public function setUp() : void {
        $this->core = new Basic\Core();
        $this->limitation = new Basic\Limitation();
        $this->particularity = new Basic\Particularity();
        $this->element = new Basic\Element($this->core, $this->limitation, $this->particularity);
        $this->element->setDescription('First element');
        $this->elementToConnect = new Basic\Element($this->core, $this->limitation, $this->particularity);
        $this->elementToConnect->setDescription('Second element');
        $this->connection = $this->element->connect($this->elementToConnect);
    }

    /**
     * @covers Basic\Element::__construct
     */
    public function testCreateElement() {
        $this->assertTrue($this->element instanceof Basic\Entity);
        $this->assertTrue($this->element instanceof Basic\Element);
    }

    /**
     * @covers Basic\Connection::__construct
     * @covers Basic\Element::connect
     * @covers Basic\Element::addConnection
     * @covers Basic\Element::getConnections
     * @covers Basic\Connection::getElements
     */
    public function testCreateConnection() {
        $this->assertTrue($this->connection instanceof Basic\Connection);
        $this->assertTrue(count($this->element->getConnections()) == 1);
        $this->assertTrue(in_array($this->connection, $this->element->getConnections()));
        $this->assertTrue(count($this->elementToConnect->getConnections()) == 1);
        $this->assertTrue(in_array($this->connection, $this->elementToConnect->getConnections()));
        $this->assertTrue(count($this->connection->getElements()) == 2);
        $this->assertTrue(in_array($this->element, $this->connection->getElements()));
        $this->assertTrue(in_array($this->elementToConnect, $this->connection->getElements()));
    }

    /**
     * @covers Basic\Structure::__construct
     * @covers Basic\Structure::getStructureElements
     * @covers Basic\Structure::getStructureConnections
     */
    public function testCreateStructure() {
        $structure = new Basic\Structure([$this->element, $this->elementToConnect], [$this->connection]);
        $this->assertTrue(count($structure->getStructureElements()) == 2);
        $this->assertTrue(count($structure->getStructureConnections()) == 1);
        $this->assertTrue(\in_array($this->element, $structure->getStructureElements()));
        $this->assertTrue(\in_array($this->elementToConnect, $structure->getStructureElements()));
        $this->assertTrue(\in_array($this->connection, $structure->getStructureConnections()));
    }

    public function testCreateStructureFromBuilder() {
        $builder = new Basic\StructureBuilder();
        $structure = $builder->setFirstElement($this->element)->build();
        $this->assertTrue(count($structure->getStructureElements()) == 2);
        $this->assertTrue(count($structure->getStructureConnections()) == 1);
        $this->assertTrue(\in_array($this->element, $structure->getStructureElements()));
        $this->assertTrue(\in_array($this->elementToConnect, $structure->getStructureElements()));
        $this->assertTrue(\in_array($this->connection, $structure->getStructureConnections()));
    }

    public function testCreateCycledStructureFromBuilder() {
        $builder = new Basic\StructureBuilder();
        $newElement = new Basic\Element($this->core, $this->limitation, $this->particularity);
        $newElement->connect($this->element);
        $newElement->connect($this->elementToConnect);
        $newElement->setDescription('Third element');
        $additionalElement = new Basic\Element($this->core, $this->limitation, $this->particularity);
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

    public function testHasDescription() {
        $structure = new Basic\Structure([$this->element, $this->elementToConnect], [$this->connection]);
        $description = 'New description';
        $structure->setDescription($description);
        $this->assertEquals($structure->getDescription(), $description);
    }
}