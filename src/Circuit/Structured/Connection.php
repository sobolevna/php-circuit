<?php

namespace Circuit\Structured; 

use Circuit\Interfaces;
use Circuit\Traits\{IsStructured, IsStructuredConnection};
use Circuit\Basic;
use Circuit\Exceptions\EntryPointConnectionException;

class Connection extends Basic\Connection implements Interfaces\Structure {

    use IsStructured;//, IsStructuredConnection;

    /**
     * @var string 
     */
    protected $description = 'Connection for structured elements';

    /**
     * @param Element[] $elements
     */
    public function __construct(array $elements) {
        parent::__construct($elements);
        $this->processElements($elements);
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
        return ($currentEntryPoint instanceof $secondClass) || ($entryPointToConnect instanceof firstClass);
    }

    protected function addStructureElement(Interfaces\Element $element) {
        if (!in_array($element, $this->structureElements, true)) {
            $this->structureElements[] = $element;
        }
    }
}