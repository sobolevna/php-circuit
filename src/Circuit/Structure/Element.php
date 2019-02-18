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
     * @var mixed 
     */
    protected $handler = null; 
    
    /**
     *
     * @var boolean
     */
    protected $isSimple = null; 
    
    protected $elementConnections;
    
    /**
     *
     * @var Structure
     */
    protected $parent;
    
    public function __construct($id = '', $map = null, $parent = null) {
        parent::__construct($id, $map);
        $this->parent = $parent;
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
        if ($state && !($state instanceof State)) {
            throw new Exception('You should process either a state ot nothing');
        }
        elseif(!$this->isSimple()) {
            return parent::process($state);
        }
        else {
            return $this->handler->process($state);
        }
    }    
    
    /**
     * 
     * @param State $state A state to process
     * @param string $from ID of the element from which processor function has been called
     * @param array $path A list of previous elements having processed the state
     * @return State
     */
    public function process($state = null) {
        $currentState = $this->getCurrentState($state);
        $path = $state->path;
        $path[] = $this->id;
        $currentState->from = $this->id;
        $currentState->path = $path;
        $this->state = $currentState;
        return $this->state;        
    }
        
    /**
     * Converts element as a node. 
     * A node will return $this.
     * Filled empty fields can't be converted. 
     * Entry points are not to have external connections.
     * @return \Circuit\Structure\Element\Node
     */
    public function toNode() {
        return new Node($this->id, $this->map);
    }
    
    public function toEntryPoint() {
        return new EntryPoint($this->id, $this->map);
    }
    
    public function toEmptyField() {
        return new EmptyField($this->id, $this->map);
    }
    
    /**
     * 
     * @return Structure
     */
    public function setHandler($handler) {
        if ($handler) {
            $this->handler = $handler;
        }
        else {
            $this->handler = null;
        }
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
    
    protected function prepareMap() {
        $map = [
            'elements'=>[],
            'connections'=>[]
        ];
        if ($this instanceof Node) {
            $map['elements']['nodes'][] = $this->getMap();
        }
        elseif ($this instanceof EmptyField) {
            $map['elements']['emptyFields'][] = $this->getMap();
        }
        elseif ($this instanceof EntryPoint) {
            $map['elements']['entryPoints'][] = $this->getMap();
        }
        return $map;
    }
    
    protected function correctMap($map) {        
        foreach (['nodes', 'emptyFields', 'entryPoints'] as $elementType) {
            if (empty($map['elements'][$elementType])) {
                continue;
            }
            $map['elements'][$elementType] = array_unique($map['elements'][$elementType], SORT_REGULAR);
        }
        $map['connections'] = !empty($map['connections']) ? array_unique($map['connections'], SORT_REGULAR) : $map['connections'];
        return $map;
    }
    
    /**
     * Recursively uses all element's connections to form a structure
     * @todo Algorythm needs optimizing
     * @param boolean $justMap
     * @param boolean $useExternal
     * @param boolean $useEmptyFields
     * @param array $from
     * @return mixed
     */
    public function formStructure($justMap = true, $useExternal = false, $useEmptyFields = false, $from = []) {
        $from[] = $this->id;
        $map = $this->prepareMap();
        foreach ($this->elementConnections as $conn) {            
            $element = $conn->getThrough($this->id);
            if (!$element || in_array($element->info()['id'], $from)) {
                continue;
            }
            $map['connections'][] = $conn->getMap();
            $map = array_merge_recursive($map, $element->formStructure($justMap, $useExternal, $useEmptyFields, $from));
        }        
        $correctMap = $this->correctMap($map);
        return $justMap || count($from)> 0 ? $correctMap : new Structure('', $correctMap);
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
    
    /**
     * 
     * @return $this
     */
    public function element() {
        return $this;
    }
}
