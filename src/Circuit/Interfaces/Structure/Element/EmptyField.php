<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Circuit\Interfaces\Structure\Element;

use Circuit\Interfaces\Structure;
use Circuit\Interfaces\Structure\Element;
use Circuit\Interfaces\Structure\Element\EntryPoint;

/**
 * Empty field is empty by default 
 * -- it returns the same state it got to process. 
 * You may fill it with any structures 
 * but don't forget to connect them with external elements: 
 * after the filling their processor function will be used instead
 * @author sobolevna
 */
interface EmptyField extends Element {
    
    /**
     * 
     * @param Structure $structure
     * @param EntryPoint[] $structureEntryPoints
     * @param EntryPoint[] $fieldEntryPoints
     * @param array $connectionInterfaceMap
     * @return bool
     */
    public function fill(Structure &$structure, array $structureEntryPoints = null, array $fieldEntryPoints = null, array $connectionInterfaceMap = null) : bool;
    
}
