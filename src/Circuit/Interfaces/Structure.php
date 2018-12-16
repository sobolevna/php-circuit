<?php

namespace Circuit\Interfaces;

use Circuit\Interfaces\Structure\{State, Connection}; 
use Circuit\Interfaces\Structure\Element\{EntryPoint, EmptyField};

/**
 * Structure interface is a basis for others.
 * Every element or connection between them also are structures
 *
 * @author sobolevna
 */
interface Structure {
    
    /**
     * 
     * @return State
     */
    public function getState() : State;
    
    /**
     * Connects to other structure using all available entry points
     * @param \Circuit\Interfaces\Structure $connectWith
     * @param array $connectionMap 
     * @return Connection 
     */
    public function connect(Structure $connectWith, array $connectionMap = null) : Connection;
    
    /**
     * Processes a state from structure context 
     * and return a new state of structure itself
     * @param State $state
     */
    public function process(State $state = null) : State; 
    
    /**
     * Inserts a structure to an empty field
     * @param EmptyField $emptyField
     * @param EntryPoint[] $sourceEntryPoints
     * @param EntryPoint[] $targetEntryPoints
     * @param array $connectionInterfaceMap 
     * @return bool
     */
    public function insertTo(EmptyField $emptyField, array $sourceEntryPoints = null, array $targetEntryPoints = null, array $connectionInterfaceMap = null) : bool; 
    
    /**
     * Casts structure to an element
     * @return Element  
     */
    public function element() : Element;
    
    /**
     * Returns information about the structure
     */
    public function info();
    
}
