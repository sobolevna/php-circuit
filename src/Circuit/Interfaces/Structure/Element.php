<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Circuit\Interfaces\Structure;

use Circuit\Interfaces\Structure;
use Circuit\Interfaces\Structure\Element\{Node, EmptyField, EntryPoint};

/**
 * 
 * @author sobolevna
 */
interface Element extends Structure{
    
    /**
     * Returns an object which is a structure element 
     * In most cases, it will just return $this
     * @return Structure 
     */
    public function instance(): Structure; 
    
    /**
     * @return Node Description
     */
    public function toNode() : Node;
    
    /**
     * @return Node Description
     */
    public function toEmptyField() : EmptyField;
    /**
     * @return Node Description
     */
    public function toEntryPoint() : EntryPoint;
}
