<?php

namespace Circuit\Complex;

use Circuit\{Interfaces, Traits};

class Mediator extends Element implements Interfaces\Connection {

    use Traits\IsConnection;

    public function __construct(Interfaces\Structure $structure, array $elements = []) {
        parent::__construct($structure);
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

    protected function makeConnections(array $entryPointArrays) {
        $entryPointArraysToConnect = $entryPointArrays;
        $currentEntryPointArray = $this->getLimitation();
        if (empty($entryPointArraysToConnect)) {
            return;
        }
        foreach ($entryPointArraysToConnect as $entryPointArray) {
            foreach ($currentEntryPointArray as $currentEntryPoint) {
                $cnt += $this->connectElementToArray($currentEntryPoint, $entryPointArray);
            }
                     
        }
    }

    protected function connectElementToArray($currentEntryPoint, $entryPointArray) {
        $cnt = 0;
        try {
            foreach ($entryPointArray as $entryPoint) {
                if ($this->checkTypes($currentEntryPoint, $entryPoint)) {
                    $this->structureConnections[]=$currentEntryPoint->connect($entryPoint);
                    $this->addStructureElement($currentEntryPoint);
                    $this->addStructureElement($entryPoint);
                    $cnt++;
                }   
            }
        } 
        catch (EntryPointConnectionException $e) {
            echo $e->getMessage();
        }
        return $cnt;
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