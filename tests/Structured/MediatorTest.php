<?php 

namespace Circuit\Tests\Structured;

use Circuit\{Factory, Interfaces, Basic, Structured};

class MediatorTest extends \PHPUnit\Framework\TestCase {

    public function testConstructElement() {
        $element = Factory::createStructuredElement(Structured\Mediator::class);
        $this->assertTrue($element instanceof Interfaces\Element);
        $this->assertTrue($element instanceof Interfaces\Connection);
        $this->assertTrue($element instanceof Interfaces\Structure);
        $this->assertCount(3, $element->getStructureElements());
        $this->assertCount(2, $element->getStructureConnections());
    }

    public function testConstructConnection() {
        $element = Factory::createStructuredElement();
        $elementToConnect = Factory::createStructuredElement();
        $connection = new Structured\Mediator(
            Factory::createBasicElement(Basic\Node::class), 
            Factory::createBasicElement(Basic\EntryPoint::class), 
            Factory::createBasicElement(Basic\EmptyField::class), 
            [$element, $elementToConnect]
        );
        $this->connectionAssertions($element, $elementToConnect, $connection);
    }

    public function testConnectMediators() {
        $element = Factory::createStructuredElement(Structured\Mediator::class);
        $elementToConnect = Factory::createStructuredElement(Structured\Mediator::class);
        $connection = new Structured\Mediator(
            Factory::createBasicElement(Basic\Node::class), 
            Factory::createBasicElement(Basic\EntryPoint::class), 
            Factory::createBasicElement(Basic\EmptyField::class), 
            [$element, $elementToConnect]
        );
        $this->connectionAssertions($element, $elementToConnect, $connection);
    }

    protected function connectionAssertions($element, $elementToConnect, $connection) {
        $this->assertTrue(in_array($connection, $element->getConnections(), true));
        $this->assertTrue(in_array($connection, $elementToConnect->getConnections(), true));
        $this->assertTrue(in_array($element, $connection->getElements(), true));
        $this->assertTrue(in_array($elementToConnect, $connection->getElements(), true));
        $this->assertCount(2, $connection->getElements());
        $this->assertTrue(in_array($element->getLimitation(), $connection->getStructureElements(), true));
        $this->assertTrue(in_array($elementToConnect->getLimitation(), $connection->getStructureElements(), true));
    }
}