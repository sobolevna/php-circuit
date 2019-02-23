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

namespace Circuit\Structure;

use \Circuit\Structure;
use \Circuit\Structure\{
    State,
    Process,
    Connection
};
use \Circuit\Structure\Element\{
    Node,
    EntryPoint,
    EmptyField
};
use \Circuit\Structure\Exception\Map as Exception;

/**
 * Description of Map
 *
 * @author sobolevna
 */
class Map {

    /**
     *
     * @var string|number
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $type;

    /**
     *
     * @var string
     */
    protected $class;

    /**
     *
     * @var array
     */
    protected $elements = [];

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
     * @var State 
     */
    protected $state;

    /**
     *
     * @var array
     */
    protected $fields = ['id', 'type', 'class','elements', 'connections', 'processes', 'state']; 
    
    /**
     *
     * @var array
     */
    protected $defaultClasses = [
        'structure' => Structure::class,
        'node' => Node::class,
        'entryPoint' => EntryPoint::class,
        'emptyField' => EmptyField::class,
        'connection' => Connection\Inner\Element\Simple::class,
        'state' => State::class,
        'process' => Process::class
    ];
    
    /**
     * 
     * @param mixed $mapContents array or JSON string
     * @throws Exception
     */
    public function __construct($mapContents = null) {
        $this->validator = new Map\Validator($this); 
        if (!empty($mapContents)) {
            (new Map\Builder($this))->make($mapContents);
        }
    }
    
    /**
     * 
     * @return array
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * 
     * @param string $key
     * @param mixed $value
     * @return mixed
     * @throws Exception
     */
    public function addMapContent($key, $value) {
        if (!property_exists($this, $key)) {
            throw new Exception('Invalid property: '. $key);
        }
        if(!$this->validator->checkMapContents($key, $value)) {
            throw new Exception('The map has invalid field: ' . $key);
        }
        $this->{$key} = $value;
    }
    

    /**
     * Converts map to array 
     * @return array
     */
    public function toArray() {
        $map = [];
        foreach ($this->fieldsToConvert as $field) {
            if (!is_array($this->{$field})) {
                $map[$field] = $this->{$field}->toArray();
            } elseif ($field == 'elements') {
                $map[$field] = [];
            } else {
                $map[$field] = $this->arrayFieldToArrayMap($this->{$field});
            }
        }
        return $map;
    }

    /**
     * 
     * @param array $field
     * @return array
     */
    protected function arrayFieldToArrayMap($field) {
        $map = [];
        foreach ($field as $value) {
            $map[] = $value->toArray();
        }
        return $map;
    }

    /**
     * 
     * @param array $field
     * @return array
     */
    protected function elementsToArrayMap($field) {
        $map = [];
        foreach (['emptyFields', 'entryPoints', 'nodes'] as $type) {
            $map[$type] = $this->arrayFieldToArrayMap($field[$type]);
        }
        return $map;
    }

    /**
     * Converts array view of the map to JSON
     * @return string
     */
    public function toJson() {
        return json_encode($this->toArray());
    }

    /**
     * Htturns JSON
     * @return string
     */
    public function __toString() {
        return $this->toJson();
    }
    
    /**
     * Creates a new structure from this
     * @return Structure
     * @throws Exception
     */
    public function toStructure() {
        if ($this->class) {
            $class = $this->class;
            return new $class($this->id, $this);
        }
        if (!empty($this->defaultClasses[$this->type])) {
            $class = $this->defaultClasses[$this->type];
            return new $class($this->id, $this);
        } 
        throw new Exception('Invalid structure type: '.$this->type);
    }
    
    public function getById($id) {
        
    }

}
