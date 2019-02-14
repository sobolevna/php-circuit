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

namespace Circuit\Structure\Map;

use Circuit\Structure;
use Circuit\Structure\Map;
use Circuit\Structure\Exception\Map as Exception;

/**
 * Description of Builder
 *
 * @author sobolevna
 */
class Builder {

    /**
     *
     * @var Map
     */
    protected $map;
    
    /**
     *
     * @var array
     */
    protected $mapFields = [];
    
    /**
     *
     * @var array
     */
    protected $allowedTypes = ['structure', 'element', 'connection', 'node', 'entryPoint', 'emptyField', 'process', 'state']; 

    public function __construct($map = null) {
        if ($map && $map instanceof Map) {
            $this->map = $map;
            $this->mapFields = $this->map->getFields();
        }
        else {
            $this->reset();
        }
    }
    
    /**
     * 
     * @param string $mapClass
     */
    public function reset($mapClass = Map::class) {
        if (class_exists($mapClass) && in_array(Map::class, class_parents($mapClass))) {
            $this->map = new $mapClass();
            $this->mapFields = $this->map->getFields();
        }
        throw new Exception('Invalid map class');
    }
    
    /**
     * 
     * @param mixed $mapContents
     * @return array
     * @throws Exception
     */
    protected function validatePrimarily($mapContents) {
        if (is_string($mapContents)) {
            $map = json_decode($mapContents, true);
            if (!$map) {
                throw new Exception('The given string is not a valid JSON');
            }
        } elseif (!is_array($mapContents)) {
            throw new Exception('A map must be either an array or JSON string');
        } else {
            $map = $mapContents;
        }
        return $map;
    }


    public function make($mapContents, $reset = true) {
        $map = $this->validatePrimarily($mapContents);
        foreach ($this->mapFields as $field) {
            if (empty($map[$field])) {
                continue;
            }
            $this->addMapContent($field, $map[$field]);
        }
        $mapResult = $this->map;
        if ($reset) {
            $this->reset();
        }        
        return $mapResult;
    }
    
    
    /**
     * 
     * @param string $key
     * @param mixed $value
     * @return mixed
     * @throws Exception
     */
    protected function checkMapContent($key, $value) {
        $methodName = 'check' . ucfirst($key);
        if (!method_exists($this, $methodName)) {
            return true;
        }
        return $this->{$methodName}($value);
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $value
     * @return mixed
     * @throws Exception
     */
    public function addMapContent($key, $value) {
        if (!$this->checkMapContent($key, $value)) {
            throw new Exception('The map has invalid field: ' . $key);
        }
        $methodName = 'add' . ucfirst($key);
        if (!method_exists($this, $methodName)) {
            if (in_array($key, $this->mapFields)) {
                $this->map->addMapContent($key, $value);
            } else {
                throw new Exception('There is no such a property: ' . $key);
            }
        } else {
            $this->map->addMapContent($key, $this->{$methodName}($value));
        }
    }
    
    
    /**
     * 
     * @param mixed $id
     * @return boolean
     */
    protected function checkId($id) {
        if ($id && (is_string($id) || is_numeric($id))) {
            return true;
        }
        return false;
    }

    /**
     * 
     * @param string $type
     * @return boolean
     */
    protected function checkType($type) {
        if (class_exists($type) && in_array(Structure::class, class_parents($type))) {
            return true;
        } elseif (in_array($type, $this->allowedTypes)) {
            return true;
        }
        return false;
    }

    /**
     * 
     * @param array $elements
     * @return boolean
     */
    protected function checkElements(array $elements) {
        if (empty($elements)) {
            return true;
        }
        $types = ['emptyFields', 'entryPoints', 'nodes'];
        if (!($types == array_keys($elements))) {
            return false;
        }
        foreach ($types as $type) {
            if (!is_array($elements[$type])) {
                return false;
            }
            if (!$this->checkElementsTypes($elements[$type])) {
                return false;
            }
        }
        return true;
    }

    /**
     * 
     * @param array $content
     * @param array $necessaryFields
     * @return boolean
     */
    protected function checkContentFields(array $content, array $necessaryFields) {
        if (!($necessaryFields == array_keys($content))) {
            return false;
        }
        if (!$this->checkId($content['id'])) {
            return false;
        }
        if (!$this->checkType($content['type'])) {
            return false;
        }
        return true;
    }

    /**
     * 
     * @param array $elementsOfType
     * @return boolean
     */
    protected function checkElementsTypes($elementsOfType) {
        foreach ($elementsOfType as $element) {
            if (!$this->checkContentFields($element, ['id', 'type'])) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * @todo make different builders for different types
     * @param type $map
     * @param type $type
     * @return type
     */
    protected function buildFromBuilder($map, $type = '') {
        
        return (new self())->make($map);
    }

    /**
     * 
     * @param array $elements
     */
    protected function addElements(array $elements) {
        $elementsMap = [];
        foreach ($elements as $type => $elementsOfType) {
            foreach ($elementsOfType as $element) {
                $elementsMap[$type][] = $this->buildFromBuilder($element, $type);
            }
        }
        return $elementsMap;
    }

    /**
     * 
     * @param array $connections
     * @return boolean
     */
    protected function checkConnections(array $connections) {
        if (empty($connections)) {
            return true;
        }
        foreach ($connections as $connection) {
            if (!$this->checkContentFields($connection, ['id', 'type', 'connected'])) {
                return false;
            }
            if (empty($connection['connected'])) {
                return false;
            }
        }
        return true;
    }

    /**
     * 
     * @param type $connections
     */
    protected function addConnections($connections) {
        $connectionsMap = [];
        foreach ($connections as $connection) {
            $connections[] = $this->buildFromBuilder($connection, 'connection');
        }
        return $connectionsMap;
    }

    /**
     * 
     * @param array $processes
     * @return boolean
     */
    protected function checkProcesses(array $processes) {
        if (empty($processes)) {
            return true;
        }
        foreach ($processes as $process) {
            if (!$this->checkContentFields($process, ['id', 'type'])) {
                return false;
            }
        }
        return true;
    }

    protected function addProcesses($processes) {
        $processesMap = [];
        foreach ($processes as $process) {
            $processesMap[] = $this->buildFromBuilder($process, 'process');
        }
        return $processesMap;
    }

    /**
     * 
     * @param array|null $state
     */
    protected function checkState($state) {
        if (empty($state)) {
            return true;
        }
        if (!$this->checkContentFields($state, ['id', 'type'])) {
            return false;
        }
        return true;
    }

    /**
     * 
     * @param array $state 
     */
    protected function addState($state) {
        return $this->buildFromBuilder($state, 'state');
    }
}
