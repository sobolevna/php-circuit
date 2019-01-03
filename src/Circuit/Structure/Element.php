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

namespace Circuit\Structure;

use Circuit\Structure;
use Circuit\Structure\{State, Connection};
use Circuit\Structure\Exception\Element as Exception;
use Circuit\Structure\Element\{Node, EmptyField, EntryPoint};

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
    
    protected $isSimple = null; 
    
    protected $elementConnections;

    /**
     * 
     * @param mixed $instance
     * @param array $map
     */
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
     * 
     * @param Structure $connectWith
     * @param array $connectionMap
     * @param string $id
     * @return Connection 
     */
    public function connect($connectWith, array $connectionMap = null, $id = '') {
        return $this->builder->connection($this, $connectWith, $connectionMap, $id, 'element');
    }
    
    /**
     * Initial processing for a state from outside.
     * @param State $state
     * @return State
     */
    protected function getCurrentState($state) {
        $value = $state instanceof State ? $state->value() : $state;
        if (!$value) {
            $value = [];
        }
        elseif (!is_array($value)) {
            $value = [$value];
        }
        $value[] = $this->id.'_'.microtime();
        $currentState = new State($value);
        if ($this->instance) {
            return $this->instance->process($currentState);
        }
        elseif(!$this->isSimple()) {
            return parent::process($currentState);
        }
        else {
            return $currentState;
        }
    }
    
    /**
     * 
     * @param State $state A state to process
     * @param string $from ID of the element from which processor function has been called
     * @param array $path A list of previous elements having processed the state
     * @return State
     */
    public function process($state = null, $from = '', $path = []) {
        $currentState = $this->getCurrentState($state);
        $cnt = 0;
        //Go through each connection and make the elements from the other side process the current state
        foreach ($this->elementConnections as $connection) {
            $element = $connection->getThrough($this->id);
            $cnt++;
            if ($element->info()['id'] == $from && count($this->elementConnections) >= $cnt) {
                $this->state = $currentState;
                return $this->state;
            }       
            elseif ($cnt <= count($this->elementConnections) && !in_array($this->id, $path)) {
                $path[] = $this->id;
                $currentState = $element->process($currentState, $this->id, $path);
            }   
            else {
                continue;
            }
        }
        $this->state = $currentState;
        return $this->state;
    }
    
    /**
     * Converts element as a node. 
     * A node will return $this.
     * True empty fields (without all their internal entry points with structures) 
     * can't be converted. 
     * Entry points are not to have external connections.
     * @return \Circuit\Structure\Element\Node
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
    
    public function isSimple() {
        if ($this->isSimple !== null) {
            return $this->isSimple;
        }
        $nodeCount = count($this->nodes);
        $fieldCount = count($this->emptyFields);
        $pointCount = count($this->entryPoints);
        $this->isSimple = !($nodeCount || $fieldCount || $pointCount);
        return $this->isSimple; 
    } 
    
    public function formStructure($justMap = true, $useExternal = false, $useEmptyFields = false, $from = []) {
        $from[] = $this->id;
        $map = [];
        if ($this instanceof Node) {
            $map['elements']['nodes'][] = $this->getMap();
        }
        elseif ($this instanceof EmptyField) {
            $map['elements']['emptyFields'][] = $this->getMap();
        }
        elseif ($this instanceof EntryPoint) {
            $map['elements']['entryPoints'][] = $this->getMap();
        }
        foreach ($this->elementConnections as $conn) {            
            $element = $conn->getThrough($this->id);
            if (!$element || in_array($element->info()['id'], $from)) {
                continue;
            }
            $map['connections'][] = $conn->getMap();
            $map = array_merge_recursive($map, $element->formStructure($justMap, $useExternal, $useEmptyFields, $from));
        }
        return $justMap || count($from)> 0 ? $map : new Structure('', $map);
    }
    
    /**
     * 
     * @param Connection $connection
     * @return boolean
     * @throws Exception
     */
    public function bindConnection($connection) {
        $id = $connection->info()['id'];    
        if (!empty($this->elementConnections[$id])) {
            throw new Exception('This connection already exists.');
        }
        elseif (!$connection->hasConnected($this->id)) {
            throw new Exception("This object doesn't exist in the connection.");
        }
        $this->elementConnections[$id] = $connection;
        return true;
    }
}
