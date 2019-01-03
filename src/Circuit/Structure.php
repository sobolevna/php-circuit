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

namespace Circuit;

use Circuit\Structure\Exception;
use Circuit\Structure\{Builder,State,Element, Connection};
use Circuit\Structure\Element\{Node, EmptyField, EntryPoint};

/**
 * Description of Structure
 *
 * @author sobolevna
 */
class Structure {
    
    
    protected $id;
    
    /**
     *
     * @var State 
     */
    protected $state;
    
    /**
     *
     * @var Builder 
     */
    protected $builder; 
    
    /**
     *
     * @var Element 
     */
    protected $element; 
    
    /**
     *
     * @var EntryPoint[] 
     */
    protected $entryPoints = array();
    
    /**
     *
     * @var EmptyField[] 
     */
    protected $emptyFields = array();
    
    /**
     *
     * @var Node[]
     */
    protected $nodes = array();
    
    /**
     *
     * @var Connection[]
     */
    protected $connections = [];

    public function __construct($id = '', array $map = null) {
        $this->id = $this->setId($id);
        $this->buildBuilder();
        if (!empty($map)) {
            $this->fromMap($map);
        }
        $this->state = new State();
    } 
    
    /**
     * 
     * @param mixed $id
     * @return string
     * @throws Exception
     */
    protected function setId($id) {
        if (is_string($id) || is_numeric($id)) {
            return $id;
        }
        elseif (!$id) {
            return get_class($this).'_'.microtime();
        }
        else {
            throw new Exception('Invalid id');
        }
    }
    
    protected function buildBuilder() {
        if (!$this->builder || !($this->builder instanceof Builder)) {
            $this->builder = new Builder();
        } 
    } 
    
    /**
     * 
     * @return Builder
     */
    public function builder() {
        return $this->builder;
    }
    
    /**
     * 
     * @param mixed $structureMap
     * @return Structure
     */
    protected function fromMap($structureMap) {
        $map = $this->builder->checkAndGetStructureMap($structureMap);  
        $types = ['node', 'emptyField', 'entryPoint'];
        foreach ($types as $type) {
            $array = $type.'s';
            if (empty($map['elements'][$array])) {
                continue;
            }
            foreach ($map['elements'][$array] as $elementMap) {
                $el =  $this->builder->fromMap($elementMap, $type); 
                $this->{$array}[$el->info()['id']] = $el;
            }
        }
        foreach ($map['connections'] as $conn) {
            $structure1 = $this->getElementById($conn['connected'][0]);
            $structure2 = $this->getElementById($conn['connected'][1]);
            if (!$structure1 || !$structure2) {
                throw new Exception('Both connected elements must exist in the structure map');
            }
            $this->connections[$conn['id']] = $this->builder->connection($structure1, $structure2, !empty($conn['map']) ? $conn['map'] : null, $conn['id']);
        }        
    } 
    
    /**
     * Recursively converts a structure to a map
     * @param bool $toJson
     * @return array|string
     */
    public function toMap($toJson = false) {
        $map = [
            'id' => $this->id,
            'elements' => [
                'nodes' => [],
                'emptyFields' => [],
                'entryPoints' => []
            ], 
            'connections' => [],
            'state' =>$this->state->toMap(),
            'instance' => get_class($this)
        ];
        foreach (['nodes', 'emptyFields', 'entryPoints'] as $type) {
            foreach ($this->$type as $key=>$value) {
                $map['elements'][$type][$key] = $value->toMap();
            }
        }
        foreach ($this->connections as $key=>$value) {
            $map['connections'][$key] = $value->toMap();
        }
        return $toJson ? json_encode($map) : $map;
    }
    
    /**
     * 
     * @param mixed $state
     * @return State
     */
    public function process($state = null) {
        $ret = [];
        foreach ($this->entryPoints as $p) {
            $ret[] = $p->process($state instanceof State ? $state : new State($state));
        }
        return new State($ret);
    }
    
    public function element() {
        if($this instanceof Element) {
            return $this;
        }
        if (!$this->element || !($this->element instanceof Element)) {
            $this->element = new Element($this);
        }
        return $this->element;
    }
    
    /**
     * Inserts a structure to an empty field
     * @param EmptyField $emptyField
     * @param EntryPoint[] $sourceEntryPoints
     * @param EntryPoint[] $targetEntryPoints
     * @param array $connectionInterfaceMap 
     * @return bool
     */
    public function insertTo($emptyField, array $sourceEntryPoints = null, array $targetEntryPoints = null, array $connectionInterfaceMap = null) {
        try {
            $sourceEP = empty($sourceEntryPoints) ? $this->entryPoints() : $sourceEntryPoints;
            $targetEP = empty($targetEntryPoints) ? $emptyField->internalEntryPoints() : $targetEntryPoints; 
            $map = $this->connectionInterfaceMap($sourceEntryPoints, $connectionInterfaceMap);
            foreach ($sourceEP as $key => $ep) {
                $ret = $ep->connectExternal($targetEP[$key], $map[$key]);
                if (!$ret) {
                    return false;
                }
            }
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
       
    }
    
    public function entryPoints() {
        return $this->entryPoints;
    }
    
    /**
     * 
     * @return State
     */
    public function getState() {
        return $this->state;
    }
    
    public function connect($connectWith, array $connectionMap = null, $id = '') {
        return $this->builder->connection($this, $connectWith, $connectionMap, $id);
    }
    
    public function info() {
        return ['id'=> $this->id];
    }    
    
    /**
     * Addends element to the structure 
     * @todo How do we append it with its all connections?
     * @param EmptyField|EntryPoint|Node $element
     * @param string $id
     * @throws Exception
     */
    public function append(&$element, $id = '') {
        $elementId = $id ? $id : $element->info()['id'];
        if ($this->getElementById($elementId)) {
            throw new Exception('Element with this id already exists in the structure.');
        }
        if ($element instanceof EmptyField) {
            $this->emptyFields[$elementId] = $element;
        } 
        elseif ($element instanceof EntryPoint) {
            $this->entryPoints[$elementId] = $element;
        }
        elseif ($element instanceof Node) {
            $this->nodes[$elementId] = $element;
        }
        else {
            throw new Exception('You can append to a structure onle specified elements -- Nodes, Entry points or Empty Fields');
        }
    }
    
    /**
     * Get element of the structure by its ID. 
     * Doesn't work with EmptyFeild content.
     * @param type $id
     * @return type
     */
    public function getElementById($id) {
        if (!empty($this->nodes[$id])) {
            return $this->nodes[$id];
        }
        if (!empty($this->emptyFields[$id])) {
            return $this->emptyFields[$id];
        }
        if (!empty($this->entryPoints[$id])) {
            return $this->entryPoints[$id];
        }
        if (!empty($this->connections[$id])) {
            return $this->connections[$id];
        }        
    }
        
}