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
use Circuit\Structure\{Builder,State,Element, Connection, Map};
use Circuit\Structure\Element\{Node, EmptyField, EntryPoint};

/**
 * Description of Structure
 *
 * @author sobolevna
 */
class Structure {
    
    /**
     * 
     */
    const ELEMENT_TYPES = ['node', 'entryPoint', 'emptyField'];
    
    /**
     *
     * @var string|number
     */
    protected $id;
    
    /**
     * A map from which the structure was built
     * @var Map 
     */
    protected $map;

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
    
    /**
     *
     * @var Process[]
     */
    protected $processes = []; 
    
    /**
     *
     * @var boolean 
     */    
    protected $isSimple; 
    
    /**
     *
     * @var string 
     */
    protected $type = 'structure';

    public function __construct($id = '', Map $map = null) {
        $this->id = $this->setId($id);
        $this->buildBuilder();
        if (!empty($map)) {
            $this->fromMap($map);
        }
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
        throw new Exception('Invalid id');
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
     * @return array
     * @throws Exception
     */
    protected function checkAndGetStructureMap($structureMap) {
        if (is_string($structureMap)) {
            $map = json_decode($structureMap, true);
            if (!$map) {
                throw new Exception('The given string is not a valid JSON');
            }
        } elseif (!is_array($structureMap)) {
            throw new Exception('A map must be either an array or JSON string');
        } else {
            $map = $structureMap;
        }
        if (!array_key_exists('elements', $structureMap) && !array_key_exists('nodes', $map['elements']) && !array_key_exists('emptyFields', $map['elements']) && !array_key_exists('entryPoints', $map['elements'])) {
            throw new Exception('The map has no elements');
        }
        if (empty($map['connections'])) {
            throw new Exception('There should be at least one connection');
        }
        return $map;
    }
    
    /**
     * 
     * @param mixed $structureMap
     * @return Structure
     */
    protected function fromMap($structureMap) {
        $map = $this->checkAndGetStructureMap($structureMap);  
        $this->map = $map;
        foreach (self::ELEMENT_TYPES as $type) {
            $array = $type.'s';
            if (empty($map['elements'][$array])) {
                continue;
            }
            foreach ($map['elements'][$array] as $elementMap) {
                $this->{$array}[$elementMap['id']] = $this->builder->fromMap($elementMap, $type); 
            }
        }
        foreach ($map['connections'] as $conn) {
            $this->connectionsFromMap($conn);
        }        
    } 
    
    protected function connectionsFromMap($conn) {
        $structure1 = $this->getById($conn['connected'][0]);
        $structure2 = $this->getById($conn['connected'][1]);
        if (!$structure1 || !$structure2) {
            throw new Exception('Both connected elements must exist in the structure map');
        }
        $this->connections[$conn['id']] = $this->builder->connection($structure1, $structure2, !empty($conn['map']) ? $conn['map'] : null, $conn['id']);
    }


    /**
     * Gets structure map and builds it if there is none.
     * @param boolean $toJson
     * @param boolean $force 
     * @return array|string
     */
    public function getMap($toJson = false, $force = false) {
        $this->map = !$this->map || $force ? $this->toMap($force) : $this->map;   
        return $toJson ? $this->map->toJson() : $this->map; 
    }    
    
    /**
     * Recursively converts a structure to a map
     * @return array
     */
    protected function toMap($force = false) {
        $map = [
            'id' => $this->id,
            'state' => $this->state ? $this->state->getMap(false, $force) : '',
            'type' => $this->type,
            'class' => get_class($this),
            'processes' => []
        ];
        foreach (self::ELEMENT_TYPES as $type) {
            foreach ($this->{$type.'s'} as $element) {
                $map['elements'][$type.'s'][] = $element->getMap(false, $force);
            }
        }
        foreach ($this->connections as $connection) {
            $map['connections'][] = $connection->getMap(false, $force);
        }
        foreach ($this->processes as $process) {
            $map['processes'][] = $process->getMap(false, $force);
        }
        return $map;
    }
    
    /**
     * 
     * @param mixed $state
     * @return State
     */
    public function process($state = null) {
        if (empty($this->processes)) {
            $this->processes = $this->builder->buildProcessMap($this);
        }
        $ret = [];
        $currentState = $state instanceof State ? $state : new State($state);
        foreach ($this->processes as $process) {
            $currentState = $process->process($currentState);
            $ret[] = $currentState->value();
        }
        return $this->state = new State($ret);
    }
    
    /**
     * 
     * @return State
     */
    public function getState() {
        return $this->state;
    }
    
    public function info($key = null) {
        $info = $this->getInfo();
        if ($key && !empty($info[$key])) {
            return $info[$key];
        }
        if ($key && empty($info[$key])) {
            return false;
        }
        return $info;
    }    
    
    protected function getInfo() {
        return ['id'=> $this->id];
    }
    
    /**
     * Get element of the structure by its ID. 
     * Doesn't work with EmptyFeild content.
     * @param string $id
     * @return Structure
     */
    public function getById($id) {
        foreach (self::ELEMENT_TYPES as $elementType) {
            if (!empty($this->{$elementType.'s'}[$id])) {
                return $this->{$elementType.'s'}[$id];
            }
        }
        if (!empty($this->connections[$id])) {
            return $this->connections[$id];
        }    
        if (!empty($this->processes[$id])) {
            return $this->processes[$id];
        }        
        return null;
    }
    
    public function isSimple() {
        if (!($this->isSimple === null)) {
            return $this->isSimple;
        }
        foreach (self::ELEMENT_TYPES as $elementType) {
            foreach ($this->{$elementType.'s'} as $element) {
                if(!($element->isSimple())) {
                    $this->isSimple = false;
                    return false;
                }
            }
        }
        return true;
    }
        
}
