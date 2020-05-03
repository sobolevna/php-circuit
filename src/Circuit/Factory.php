<?php 

namespace Circuit;

use Circuit\{Basic, Structured, Complex, Advanced};


class Factory {

    public static function createSimpleStructure() {
        list('node'=>$node, 'entryPoint'=>$entryPoint, 'emptyField'=>$emptyField) = self::createMainParts('Structured');
        $node->connect($entryPoint);
        $node->connect($emptyField);
        $builder = new Basic\StructureBuilder();
        return $builder->setFirstElement($entryPoint)->build();
    }

    public static function createCycledStructure() {
        list('node'=>$node, 'entryPoint'=>$entryPoint, 'emptyField'=>$emptyField) = self::createMainParts('Structured');
        $node->connect($entryPoint);
        $node->connect($emptyField);
        $builder = new Basic\StructureBuilder();
        $secondNode = self::createStructuredElement(Structured\Node::class); 
        $secondNode->connect($node);
        $secondNode->connect($entryPoint);
        $secondNode->connect($emptyField);
        $secondEntryPoint = self::createStructuredElement(Structured\EntryPoint::class); 
        $secondNode->connect($secondEntryPoint);
        return $builder->setFirstElement($entryPoint)->build();
    }

    public static function createFractalizedStructure() {
        list('node'=>$node, 'entryPoint'=>$entryPoint, 'emptyField'=>$emptyField) = self::createMainParts('Structured');
        $node->connect($entryPoint);
        $node->connect($emptyField);
        $builder = new Basic\StructureBuilder();
        $secondNode = self::createElement(Structured\Node::class); 
        $secondNode->connect($node);
        $secondNode->connect($entryPoint);
        $secondNode->connect($emptyField);
        $secondEntryPoint = self::createElement(Structured\EntryPoint::class);
        $secondNode->connect($secondEntryPoint);
        return $builder->setFirstElement($entryPoint)->build();
    }

    public static function createElement($class = Structured\Element::class) {
        $core = new Basic\Core();
        $limitation = new Basic\Limitation();
        $particularity = new Basic\Particularity();
        $node = new Basic\Node($core, $limitation, $particularity);
        $entryPoint = new Basic\EntryPoint($core, $limitation, $particularity);
        $emptyField = new Basic\EmptyField($core, $limitation, $particularity);
        return new $class($node, $entryPoint, $emptyField);
    }

    public static function createBasicElement($class = Basic\Element::class) {
        $core = new Basic\Core();
        $limitation = new Basic\Limitation();
        $particularity = new Basic\Particularity();
        return new $class($core, $limitation, $particularity);
    }

    public static function createStructuredElement($class = Structured\Element::class) {
        list('node'=>$node, 'entryPoint'=>$entryPoint, 'emptyField'=>$emptyField) = self::createMainParts();
        return new $class($node, $entryPoint, $emptyField);
    }

    public static function createSimpleComplexElement($class = Complex\Element::class) {
        return new $class(self::createSimpleStructure());
    }

    public static function createStructuredConnectionElement() {
        list('node'=>$node, 'entryPoint'=>$entryPoint, 'emptyField'=>$emptyField) = self::createMainParts();
        return new Advanced\StructuredConnectionElement($node, $entryPoint, $emptyField);
    }

    protected function createMainParts($prefix = 'Basic') {
        $namespace = ucfirst($prefix);
        $method = "create{$namespace}Element";
        return [
            'node' => self::$method("\Circuit\\$namespace\Node"),
            'entryPoint' => self::$method("\Circuit\\$namespace\EntryPoint"),
            'emptyField' => self::$method("\Circuit\\$namespace\EmptyField"),
        ];
    }
}