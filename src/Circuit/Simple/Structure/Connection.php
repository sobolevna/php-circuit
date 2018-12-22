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

use Circuit\Simple\{Exception, Structure};
use Circuit\Simple\Structure\Element\EntryPoint;


/**
 * Description of Connection
 *
 * @author sobolevna
 */
class Connection extends Structure {
    
    /**
     *
     * @var Structure[] 
     */
    protected $connected;
    
    /**
     * If the connection is between 2 empty entry points
     * @var bool 
     */
    protected $isStraight = false;

    public function __construct($id, &$structure1, &$structure2) {
        if ($this->checkConnectionTypes($structure1,$structure2)) {
            $connectionId = !$id ? $structure1->info()['id'].'-<|>-'.$structure2->info()['id'] : $id;
            $this->connected[$structure1->info()['id']] = &$structure1;
            $this->connected[$structure2->info()['id']] = &$structure2;
            if ($structure1->element() instanceof EntryPoint && $structure1->element()->isElementary() && $structure2->element() instanceof EntryPoint && $structure2->element()->isElementary()) {
                $this->isStraight = true;
            }
            parent::__construct($connectionId, null); 
            $structure1->bindConnection($this);
            $structure2->bindConnection($this);
        }
        else {
            throw new Exception('Invalid types for connection.');
        }
    }
    
    public function info() {
        $info = parent::info();
        $info['isStraight'] = $this->isStraight;
        return $info;
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
        if (!($structure1 instanceof Element && $structure2 instanceof Element)){
            return false;
        }            
        elseif ($structure1 instanceof Element\Node || $structure2 instanceof Element\Node) {
            return true;
        }
        elseif ($structure1 instanceof Element\EntryPoint && $structure2 instanceof Element\EntryPoint) {
            return true;
        }
        return false;
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
    public function getElementById($id) {
        if (!empty($this->connected[$id])) {
            return $this->connected[$id];
        }
        return parent::getElementById($id);
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
     * @param bool $toJson
     * @return array|string
     */
    public function toMap($toJson = false) {
        $map = parent::toMap(false);
        $connected = [];
        foreach ($this->connected as $key => $value) {
            $connected[$key] = get_class($value);
        }
        $map['connected'] = $connected;
        return $toJson ? json_encode($map) : $map;
    }
}
