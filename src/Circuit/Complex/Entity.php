<?php

namespace Circuit\Complex;

use Circuit\Interfaces;
use Circuit\Traits\IsStructured;
use Circuit\Structured;
use Circuit\Exceptions\ElementTypeException;

class Entity extends Structured\Entity {

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
            if ($element instanceof Interfaces\Node) {
                $nodes[] = $element;
            }
            elseif ($element instanceof Interfaces\EntryPoint) {
                $entryPoints[] = $element;
            }
            elseif ($element instanceof Interfaces\EmptyField) {
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