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
        $secondEntryPoint = $this->createElement(Structured\EntryPoint::class); 
        $secondNode->connect($secondEntryPoint);
        return $builder->setFirstElement($entryPoint)->build();
    }

    public function createFractalizedStructure() {
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
        $secondEntryPoint = $this->createElement(Structured\EntryPoint::class);
        $secondNode->connect($secondEntryPoint);
        return $builder->setFirstElement($entryPoint)->build();
    }

    public function createElement($class = Structured\Element::class) {
        $core = new Basic\Core();
        $limitation = new Basic\Limitation();
        $particularity = new Basic\Particularity();
        $node = new Basic\Node($core, $limitation, $particularity);
        $entryPoint = new Basic\EntryPoint($core, $limitation, $particularity);
        $emptyField = new Basic\EmptyField($core, $limitation, $particularity);
        return new $class($node, $entryPoint, $emptyField);
    }

    public function createStructuredElement($class = Element::class) {
        $node = $this->createElement(StructuredNode::class);
        $entryPoint = $this->createElement(StructuredEntryPoint::class);
        $emptyField = $this->createElement(StructuredEmptyField::class);
        return new $class($node, $entryPoint, $emptyField);
    }

    public function createSimpleComplexElement($class = ComplexElement::class) {
        $node = $this->createElement(Structured\Node::class);
        $entryPoint = $this->createElement(Structured\EntryPoint::class);
        $emptyField = $this->createElement(Structured\EmptyField::class);
        $structure = (new Basic\StructureBuilder())->setFirstElement($entryPoint)->build();
        return new $class($structure);
    }
}