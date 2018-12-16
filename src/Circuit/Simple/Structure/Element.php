<?php

/*
 * Copyright (C) 2018 sobolevna
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Circuit\Simple\Structure;

use \Circuit\Simple\Structure;
use Circuit\Interfaces\Structure\Element as ElementInterface;
use Circuit\Simple\Structure\Element\{Node, EmptyField, EntryPoint};

/**
 * Description of Element
 *
 * @author sobolevna
 */
class Element extends Structure {
    
    /**
     *
     * @var Structure 
     */
    protected $instance;
    
    public function __construct($instance = null, array $map = null) {
        if (!($instance instanceof Structure)) {
            parent::__construct($instance, $map);
            $this->instance = $this;            
        }
        else {
            $this->instance = $instance;     
            $this->builder = $instance->builder();       
        }   
    }
    
    /**
     * Converts element as a node. 
     * A node will return $this.
     * True empty fields (without all their internal entry points with structures) 
     * can't be converted. 
     * Entry points are not to have external connections.
     * @return \Circuit\Simple\Structure\Element\Node
     */
    public function toNode() {
        if ($this instanceof Node) {
            return $this;
        }
        elseif ($this instanceof EmptyField) {
            
        }
        elseif ($this instanceof EntryPoint) {
            
        }
        return new Node($this);
    }
    
    public function toEntryPoint() {
        ;
    }
    
    public function toEmptyField() {
        ;
    }
    
    /**
     * 
     * @return Structure
     */
    public function instance() {
        return $this->instance;
    }
    
    public function isElementary() {
        $nodeCount = count($this->nodes);
        $fieldCount = count($this->emptyFields);
        $pointCount = 0;
        foreach ($this->entryPoints as $point) {
            if ($point instanceof MockEntryPoint){
                continue;
            }
            $pointCount += 1;
        }
        return !($nodeCount || $fieldCount || $pointCount);
    }
}
