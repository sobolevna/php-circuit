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

namespace Circuit\Implementation\Simple;

use Circuit\Interfaces;

/**
 * Description of newPHPClass
 *
 * @author sobolevna
 */
class Structure implements Interfaces\Structure{
    
    protected $elements = []; 
    
    protected $connections = [];

    protected $map = null; 
    
    protected $id;
    
    public function __construct($id, $elements = [], $connections = []) {
        $this->id = $id; 
        foreach ($elements as $element) {
            if (!($element instanceof Element)) {
                throw new Exception('Invalid element');
            }
            $this->elements[$element->getId()] = $element;
        }
        foreach ($connections as $connection) {
            if (!($element instanceof Element)) {
                throw new Exception('Invalid connection');
            }
            $this->elements[$connection->getId()] = $connection;
        }
    }
    
    public function getElementById(string $id) : Interfaces\Element {
        if (!empty($this->elements[$id])) {
            return $this->elements[$id];
        }
        throw new Exception('Invalid element id');
    }
    
    /**
     * 
     * @return Interfaces\Element[]
     */
    public function getElements() {
        return $this->elements;
    }
    
    public function addElement(Element $element, $overrideIfExists = false) {
        if (!$element) {
            throw new Exception('The element is null');
        }
        if (!empty($this->elements[$element->getId()]) && !$overrideIfExists) {
            throw new Exception('The element already exists');
        }
        $this->elements[$element->getId()] = $element;
    } 
    
    public function addConnection(Connection $connection) {
        if (!$connection) {
            throw new Exception('The connection is null');
        }
        if (!empty($this->connections[$connection->getId()]) && !$overrideIfExists) {
            throw new Exception('The connection already exists');
        }
        $this->connections[$connection->getId()] = $connection;
    }
    
}
