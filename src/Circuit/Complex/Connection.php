<?php

namespace Circuit\Complex; 

use Circuit\Interfaces;
use Circuit\Traits\IsStructured;
use Circuit\Structured;
use Circuit\Exceptions\EntryPointConnectionException;

class Connection extends Structured\Connection {

    /**
     * @var string 
     */
    protected $description = 'Connection for complex elements';

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
        $currentEntryPointArray = array_pop($entryPointArraysToConnect);
        if (empty($entryPointArraysToConnect)) {
            return;
        }
        foreach ($entryPointArraysToConnect as $entryPointArray) {
            foreach ($currentEntryPointArray as $currentEntryPoint) {
                $cnt += $this->connectElementToArray($currentEntryPoint, $entryPointArray);
            }
                     
        }
        $this->makeConnections($entryPointArraysToConnect);
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
}