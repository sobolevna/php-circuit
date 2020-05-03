<?php 

namespace Circuit\Tests\Complex;

use Circuit\{Factory, Interfaces, Basic, Structured, Complex};

class MediatorTest extends \PHPUnit\Framework\TestCase {

    public function testConstructElement() {
        $element = Factory::createSimpleComplexElement(Complex\Mediator::class);
        $this->assertTrue($element instanceof Interfaces\Element);
        $this->assertTrue($element instanceof Interfaces\Connection);
        $this->assertTrue($element instanceof Interfaces\Structure);
        $this->assertCount(3, $element->getStructureElements());
        $this->assertCount(2, $element->getStructureConnections());
    }

    /**
     * @dataProvider providerStructure
     */
    public function testConstructConnection($structure) {
        $element = new Complex\Element(clone $structure);
        $elementToConnect = new Complex\Element(clone $structure);
        $connection = new Complex\Mediator(
            clone $structure, 
            [$element, $elementToConnect]
        );
        $this->connectionAssertions($element, $elementToConnect, $connection);
    }

    /**
     * @dataProvider providerStructure
     */
    public function testConnectMediators($structure) {
        $element = new Complex\Mediator(clone $structure);
        $elementToConnect = new Complex\Mediator(clone $structure);
        $connection = new Complex\Mediator(
            clone $structure, 
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
        $this->assertTrue(in_array($element->getLimitation()->getIterator()->current(), $connection->getStructureElements(), true));
        $this->assertTrue(in_array($elementToConnect->getLimitation()->getIterator()->current(), $connection->getStructureElements(), true));
    }

    public function providerStructure() {
        return [
            'structuredEntity' => [Factory::createSimpleStructure()],
            'cycledStructure' => [Factory::createCycledStructure()],
            'fractalizedStructure' => [Factory::createFractalizedStructure()]
        ];
    }
}