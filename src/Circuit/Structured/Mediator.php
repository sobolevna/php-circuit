<?php

namespace Circuit\Structured;

use Circuit\{Interfaces, Traits};

class Mediator extends Element implements Interfaces\Connection {

    use Traits\IsConnection;

    public function __construct(Interfaces\Node $core, Interfaces\EntryPoint $limitation, Interfaces\EmptyField $particularity, array $elements = []) {
        parent::__construct($core, $limitation, $particularity);
        if (!empty($elements)) {
            $this->elements = $elements;
            $this->addConnectionToElements($elements);
            $this->processElements($elements);
        }
    }

    protected function processElements(array $elements) {
        $entryPoints = [];
        foreach ($elements as $element) {
            $entryPoints[] = $element->getLimitation();
        }
        $this->makeConnections($entryPoints);
        if (empty($this->structureConnections)) {
            var_dump($entryPoints);
            throw new EntryPointConnectionException('No EntryPoints could be connected');
        }
    }

    protected function makeConnections(array $entryPoints) {
        $entryPointsToConnect = $entryPoints;
        $currentEntryPoint = $this->getLimitation();
        foreach ($entryPointsToConnect as $entryPoint) {
            if ($this->checkTypes($currentEntryPoint, $entryPoint)) {
                $this->structureConnections[]=$currentEntryPoint->connect($entryPoint);
                $this->addStructureElement($entryPoint);
            }            
        }
    }
    
    protected function checkTypes($currentEntryPoint, $entryPointToConnect) {
        $firstClass = get_class($currentEntryPoint);
        $secondClass = get_class($entryPointToConnect);
        return ($currentEntryPoint instanceof $secondClass) || ($entryPointToConnect instanceof firstClass);
    }

    protected function addStructureElement(Interfaces\Element $element) {
        if (!in_array($element, $this->structureElements, true)) {
            $this->structureElements[] = $element;
        }
    }
}