<?php 

namespace Circuit\Traits; 

use Circuit\Interfaces\{Element, Connection};

trait IsStructuredConnection {
    
    protected function processElements(array $elements) {
        $entryPoints = [];
        foreach ($elements as $element) {
            $entryPoints[] = $element->getLimitation();
        }
        $this->makeConnections($entryPoints);
        if (empty($this->structureConnections)) {
            throw new EntryPointConnectionException('No EntryPoints could be connected');
        }
    }

    protected function makeConnections(array $entryPoints) {
        $entryPointsToConnect = $entryPoints;
        $currentEntryPoint = array_pop($entryPointsToConnect);
        if (empty($entryPointsToConnect)) {
            return;
        }
        foreach ($entryPointsToConnect as $entryPoint) {
            if ($this->checkTypes($currentEntryPoint, $entryPoint)) {
                $this->structureConnections[]=$currentEntryPoint->connect($entryPoint);
                $this->addStructureElement($currentEntryPoint);
                $this->addStructureElement($entryPoint);
            }            
        }
        $this->makeConnections($entryPointsToConnect);
    }
    
    protected function checkTypes($currentEntryPoint, $entryPointToConnect) {
        $firstClass = get_class($currentEntryPoint);
        $secondClass = get_class($entryPointToConnect);
        return ($currentEntryPoint instanceof $secondClass) || (entryPointToConnect instanceof firstClass);
    }

    protected function addStructureElement(Interfaces\Element $element) {
        if (!in_array($element, $this->structureElements)) {
            $this->structureElements[] = $element;
        }
    }
}