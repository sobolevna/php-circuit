<?php

/*
 * Copyright (C) 2019 sobolevna
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

/**
 * Description of Validator
 *
 * @author sobolevna
 */
class Validator {

    /**
     *
     * @var Map
     */
    protected $map;     
    
    /**
     *
     * @var array
     */
    protected $allowedTypes = ['structure', 'element', 'connection', 'node', 'entryPoint', 'emptyField', 'process', 'state'];

    public function __construct($map) {
        $this->map = $map;
    }

    /**
     * 
     * @param string $key
     * @param mixed $value
     * @return mixed
     * @throws Exception
     */
    public function checkMapContent($key, $value) {
        $methodName = 'check' . ucfirst($key);
        if (!method_exists($this, $methodName)) {
            return true;
        }
        return $this->{$methodName}($value);
    }

    /**
     * 
     * @param mixed $id
     * @return boolean
     */
    protected function checkId($id) {
        if (!($id && (is_string($id) || is_numeric($id)))) {
            return false;
        }
        if ($this->map->getById($id)) {
            return false;
        }
        return true;
    }

    /**
     * 
     * @param string $type
     * @return boolean
     */
    protected function checkType($type) {
         if (in_array($type, $this->allowedTypes)) {
            return true;
        }
        return false;
    }
    
    protected function checkClass($class) {
        if (class_exists($class) && in_array(Structure::class, class_parents($class))) {
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

}
