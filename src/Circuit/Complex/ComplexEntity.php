<?php

namespace Circuit\Complex;

use Circuit\Interfaces;
use Circuit\Interfaces\Element;
use Circuit\Traits\IsStructured;
use Circuit\Structured\StructuredEntity;
use Circuit\Exceptions\ElementTypeException;

class ComplexEntity extends StructuredEntity {

    use IsStructured;

    protected $description = 'Entity that is a structure with more than three differint elements';

    public function __construct(Interfaces\Structure $structure) {
        $this->structureElements = $structure->getStructureElements();
        $this->structureConnections = $structure->getStructureConnections();
        $categorized = $this->categorizeElements();
        $this->core = new NodeArray($categorized['nodes']);
        $this->limitation = new EntryPointArray($categorized['entryPoints']);
        $this->particularity = new EmptyFieldArray($categorized['emptyFields']);
    }

    protected function categorizeElements() {
        $nodes = $entryPoints = $emptyFields = [];
        foreach ($this->structureElements as $element) {
            if ($element instanceof Element\Node) {
                $nodes[] = $element;
            }
            elseif ($element instanceof Element\EntryPoint) {
                $entryPoints[] = $element;
            }
            elseif ($element instanceof Element\EmptyField) {
                $emptyFields[] = $element;
            }
            else {
                throw new ElementTypeException("There must be only Nodes, EntryPoints and EmptyFields");
            }
        }
        return [
            'nodes' => $nodes,
            'entryPoints' => $entryPoints,
            'emptyFields' =>$emptyFields
        ];
    }
}