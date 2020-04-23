<?php

namespace Circuit\Implementation\Basic; 

use Circuit\Interfaces;
use Circuit\Traits\IsStructured;
use Circuit\Exceptions\EntryPointConnectionException;

class StructuredConnection extends Connection implements Interfaces\Structure {

    use IsStructured;

    /**
     * @var string 
     */
    protected $description = 'Connection for structured elements';

    /**
     * @param StructuredElement[] $elements
     */
    public function __construct(array $elements) {
        parent::__construct($elements);
        $entryPoints = [];
        foreach ($elements as $element) {
            $entryPoints[] = $element->getLimitation();
        }
        $this->makeConnection($entryPoints);
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
        $this->makeConnection($entryPointsToConnect);
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