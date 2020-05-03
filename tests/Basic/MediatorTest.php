<?php 

namespace Circuit\Tests\Basic;

use Circuit\{Factory, Interfaces, Basic};

class MediatorTest extends \PHPUnit\Framework\TestCase {

    public function testConstructElement() {
        $element = Factory::createBasicElement(Basic\Mediator::class);
        $this->assertTrue($element instanceof Interfaces\Element);
        $this->assertTrue($element instanceof Interfaces\Connection);
    }

    public function testConstructConnection() {
        $element = Factory::createBasicElement();
        $elementToConnect = Factory::createBasicElement();
        $connection = new Basic\Mediator(
            new Basic\Core, new Basic\Limitation, new Basic\Particularity, 
            [$element, $elementToConnect]
        );        
        $this->connectionAssertions($element, $elementToConnect, $connection);
    }

    public function testConnectMediators() {
        $element = Factory::createBasicElement(Basic\Mediator::class);
        $elementToConnect = Factory::createBasicElement(Basic\Mediator::class);
        $connection = new Basic\Mediator(
            new Basic\Core, new Basic\Limitation, new Basic\Particularity, 
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
    }
}