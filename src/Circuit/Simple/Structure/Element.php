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

use \Circuit\Simple\{Structure, Exception};
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
    protected $instance = null;
    
    public function __construct($instance = null, array $map = null) {
        if (!$instance || !($instance instanceof Structure)) {
            parent::__construct($instance, $map);     
        }
        else {
            $this->instance = &$instance;     
            $this->builder = &$instance->builder(); 
            $this->state = &$instance->getState(); 
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
        if (get_class($this) == Element::class) {
            return new Node($this);
        }
        elseif ($this instanceof Node) {
            return $this;
        }
        elseif ($this instanceof EmptyField && $this->isEmpty()) {
            return new Node($this);
        }
        elseif ($this instanceof EntryPoint && !$this->hasExternalConnection()) {
            return new Node($this);
        }
        throw new Exception('Invalid class to convert to Node');
    }
    
    public function toEntryPoint() {
        if (get_class($this) == Element::class) {
            return new EntryPoint($this);
        }
        elseif ($this instanceof EntryPoint ) {
            return $this;
        }
        elseif ($this instanceof Node) {
            return new EntryPoint($this);
        }
        elseif ($this instanceof EmptyField && $this->isEmpty()) {
            return new Node($this);
        }
        throw new Exception('Invalid class to convert to EntryPoint');
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
    
    public function formStructure($justMap = true, $useExternal = false, $useEmptyFields = false, $from = []) {
        $from[] = $this->id;
        $map = [
            'elements' => [
                'nodes' => [],
                'emptyFields' => [],
                'entryPoints' => []
            ], 
            'connections' => []
        ];
        if ($this instanceof Node) {
            $map['elements']['nodes'][] = $this->toMap();
        }
        elseif ($this instanceof EmptyField) {
            $map['elements']['emptyFields'][] = $this->toMap();
        }
        elseif ($this instanceof EntryPoint) {
            $map['elements']['entryPoints'][] = $this->toMap();
        }
        foreach ($this->connections as $conn) {
            $map['connections'][] = $conn->toMap();
            $element = $conn->getThrough($this->id);
            if (!$element || in_array($element->info()['id'], $from)) {
                continue;
            }
            $elementMap = $element->formStructure($justMap, $useExternal, $useEmptyFields, $from);
            $map = array_merge_recursive($map, $elementMap);
        }
        return $justMap || count($from)> 0 ? $map : new Structure('', $map);
    }
}
