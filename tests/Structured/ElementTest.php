<?php 

namespace Circuit\Tests\Structured;

use Circuit\Basic\StructureBuilder;
use Circuit\Complex;
use Circuit\Structured;
use Circuit\Interfaces;
use Circuit\Factory;

class ElementTest extends \PHPUnit\Framework\TestCase {

    /**
     * @covers ::__construct
     */
    public function testConstruct() {
        $element = Factory::createStructuredElement();
        $this->assertTrue($element instanceof Interfaces\Element);
        $this->assertTrue($element instanceof Interfaces\Structure);
        $this->assertCount(3, $element->getStructureElements());
        $this->assertCount(2, $element->getStructureConnections());
    }

    public function testConnect() {
        $element = Factory::createStructuredElement();
        $elementToConnect = Factory::createStructuredElement();
        $connection = new Structured\Connection(
            [$element, $elementToConnect]
        );
        //var_dump($elementToConnect->getConnections());
        $this->assertTrue(in_array($connection, $element->getConnections(), true));
        $this->assertTrue(in_array($connection, $elementToConnect->getConnections(), true));
        $this->assertTrue(in_array($element, $connection->getElements(), true));
        $this->assertTrue(in_array($elementToConnect, $connection->getElements(), true));
        $this->assertCount(2, $connection->getElements());
    }
}