<?php

namespace Circuit\Implementation\Basic; 

use Circuit\Interfaces;
use Circuit\Traits\IsStructured;
use Circuit\Exceptions\EntryPointConnectionException;

class ComplexConnection extends StructuredConnection {

    /**
     * @var string 
     */
    protected $description = 'Connection for complex elements';

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
        $this->makeConnection($entryPointArraysToConnect);
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