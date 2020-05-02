<?php 

namespace Circuit\Tests\Complex; 

use Circuit\{Interfaces, Basic, Structured, Complex};

class StructureTest extends \PHPUnit\Framework\TestCase {

    public function setUp() : void {
        $factory = new \Circuit\Tests\Factory();
        $builder = new Basic\StructureBuilder();
        $this->node = $this->createElement(Structured\Node::class);
        $this->entryPoint = $this->createElement(Structured\EntryPoint::class);
        $this->emptyField = $this->createElement(Structured\EmptyField::class);
        $this->element = new Complex\ComplexElement($builder->setFirstElement($this->entryPoint)->build());
        $this->element->setDescription('First element');
        $this->elementToConnect = $factory->createSimpleComplexElement(Complex\ComplexElement::class);
        $this->elementToConnect->setDescription('Second element');
        $this->connection = $this->element->connect($this->elementToConnect);
    }

    /**
     * @covers Structured\Element::__construct
     */
    public function testCreateElement() {
        $this->assertTrue($this->element instanceof Complex\ComplexEntity);
        $this->assertTrue($this->element instanceof Complex\ComplexElement);
        $this->assertTrue($this->element instanceof Interfaces\Structure);
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
        $this->markTestSkipped('');
        parent::testCreateConnection();
        $this->assertCount(2, $this->connection->getStructureElements());
        $this->assertCount(1, $this->connection->getStructureConnections());
    }
    
    protected function createElement() {
        $factory = new \Circuit\Tests\Factory();
        return $factory->createElement();
    }

    public function testCreateStructure() {
        $this->markTestSkipped('');
    }

    public function testCreateStructureFromfactory() {
        $this->markTestSkipped('');
    }

    public function testCreateCycledStructureFromfactory() {
        $this->markTestSkipped('');
    }

    public function testHasDescription() {
        $this->markTestSkipped('');
    }
}