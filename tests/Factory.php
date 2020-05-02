<?php 

namespace Circuit\Tests;

use Circuit\{Basic, Structured};


class Factory {

    public function createSimpleStructure() {
        $node = $this->createElement(Structured\Node::class);
        $entryPoint = $this->createElement(Structured\EntryPoint::class);
        $emptyField = $this->createElement(Structured\EmptyField::class);
        $node->connect($entryPoint);
        $node->connect($emptyField);
        $builder = new Basic\StructureBuilder();
        return $builder->setFirstElement($entryPoint)->build();
    }

    public function createCycledStructure() {
        $node = $this->createElement(Structured\Node::class);
        $entryPoint = $this->createElement(Structured\EntryPoint::class);
        $emptyField = $this->createElement(Structured\EmptyField::class);
        $node->connect($entryPoint);
        $node->connect($emptyField);
        $builder = new Basic\StructureBuilder();
        $secondNode = $this->createElement(Structured\Node::class); 
        $secondNode->connect($node);
        $secondNode->connect($entryPoint);
        $secondNode->connect($emptyField);
        return $builder->setFirstElement($entryPoint)->build();
    }

    public function createFractalizedStructure() {
        $node = $this->createStructuredElement(Structured\Node::class);
        $entryPoint = $this->createStructuredElement(Structured\EntryPoint::class);
        $emptyField = $this->createStructuredElement(Structured\EmptyField::class);
        $node->connect($entryPoint);
        $node->connect($emptyField);
        $builder = new Basic\StructureBuilder();
        $secondNode = $this->createStructuredElement(Structured\Node::class); 
        $secondNode->connect($node);
        $secondNode->connect($entryPoint);
        $secondNode->connect($emptyField);
        return $builder->setFirstElement($entryPoint)->build();
    }

    public function createElement($class = Structured\StructuredElement::class) {
        $core = new Basic\Core();
        $limitation = new Basic\Limitation();
        $particularity = new Basic\Particularity();
        $node = new Structured\Node($core, $limitation, $particularity);
        $entryPoint = new Structured\EntryPoint($core, $limitation, $particularity);
        $emptyField = new Structured\EmptyField($core, $limitation, $particularity);
        return new $class($node, $entryPoint, $emptyField);
    }

    public function createStructuredElement($class = Structured\StructuredElement::class) {
        $node = $this->createElement(Structured\StructuredNode::class);
        $entryPoint = $this->createElement(Structured\StructuredEntryPoint::class);
        $emptyField = $this->createElement(Structured\StructuredEmptyField::class);
        return new $class($node, $entryPoint, $emptyField);
    }

    public function createSimpleComplexElement($class = Complex\ComplexElement::class) {
        $node = $this->createElement(Structured\Node::class);
        $entryPoint = $this->createElement(Structured\EntryPoint::class);
        $emptyField = $this->createElement(Structured\EmptyField::class);
        $structure = (new Basic\StructureBuilder())->setFirstElement($entryPoint)->build();
        return new $class($structure);
    }
}