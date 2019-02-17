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


    public function __construct($map = null) {
        if ($map && $map instanceof Map) {
            $this->map = $map;
            $this->mapFields = $this->map->getFields();
        } else {
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
    public function addMapContent($key, $value) {
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
     * @param type $connections
     */
    protected function addConnections($connections) {
        $connectionsMap = [];
        foreach ($connections as $connection) {
            $connections[] = $this->buildFromBuilder($connection, 'connection');
        }
        return $connectionsMap;
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
     * @param array $state 
     */
    protected function addState($state) {
        return $this->buildFromBuilder($state, 'state');
    }

}
