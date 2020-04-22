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
use Circuit\Structure\Exception\Connection as Exception;


/**
 * Basic connection class with basic methods
 *
 * @author sobolevna
 */
class Connection extends Structure {
    
    /**
     *
     * @var Structure[] 
     */
    protected $connected;
    
    public function __construct($id, &$structure1, &$structure2) {
        if ($this->checkConnectionTypes($structure1,$structure2)) {
            $connectionId = !$id ? [$structure1->info()['id'], $structure2->info()['id']] : $id;
            $this->connected[$structure1->info()['id']] = &$structure1;
            $this->connected[$structure2->info()['id']] = &$structure2;
            parent::__construct($connectionId, null); 
            $structure1->bindConnection($this);
            $structure2->bindConnection($this);
        }
        else {
            throw new Exception('Invalid types for connection.');
        }
    }
    
    protected function setId($id) {
        if (!is_array($id)) {
            return parent::setId($id);
        }
        sort($id);
        if ($id[0] == $id[1]) {
            throw new Exception('You cannot connect elements with the same ID');
        }
        return $id[0].'-<|>-'.$id[1];
    }
        
    /**
     * Nodes can be connected to anything. 
     * Entry points can be connected internally to nodes, 
     * externally to other entry points.
     * Empty fields can be connected only to nodes.
     * @param Structure $structure1
     * @param Structure $structure2
     * @return boolean
     */
    protected function checkConnectionTypes($structure1, $structure2) {
        if (!($structure1 instanceof Structure && $structure2 instanceof Structure)){
            return false;
        }          
        return true;
    }    
    
    /**
     * 
     * @param mixed $object
     */
    public function hasConnected($object) {
        if (is_string($object) && !empty($this->connected[$object])) {
            return true;
        }
        elseif (class_exists($object) && ($object == Structure::class || in_array(Structure::class, class_parents($object)))) {
            foreach ($this->connected as $value) {
                if ($value instanceof $object) {
                    return true;
                }
            }
        }
        elseif (is_object($object) && $object instanceof Structure) {
            return in_array($object, $this->connected);
        }
        else {
            return false;
        }
    }
    
    /**
     * Gets element by its ID. Searches through connected structers too.
     * @param type $id
     * @return type
     */
    public function getById($id) {
        if (!empty($this->connected[$id])) {
            return $this->connected[$id];
        }
        return parent::getById($id);
    }
    
    /**
     * Returns a pair for a given element in the connect element
     * @param mixed $element 
     * @return Element 
     * @throws Exception
     */
    public function getThrough($element) {
        if (is_string($element) && !empty($this->connected[$element])) {
            $id = $element;
        }
        elseif (is_object($element) && $element instanceof Element) {
            $id = $element->info()['id'];
        }
        else {
            throw new Exception("A given element doesn't exist in the connection");
        }
        foreach ($this->connected as $key => $value) {
            if ($key != $id) {
                return $value;
            }            
        }
    }
    
    /**
     * Converts connection to map
     * @param boolean $forse
     * @return array 
     */
    protected function toMap($forse = false) {
        $map = parent::toMap($forse);
        $map['connected'] = \array_keys($this->connected);
        return $map;
    }
}
