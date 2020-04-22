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
    
    public function id() {
        return $this->id;
    }
    
    public function addElement(Element $element, $overrideIfExists = false) {
        if (!$element) {
            throw new Exception('The element is null');
        }
        if (!empty($this->elements[$element->getId()]) && !$overrideIfExists) {
            throw new Exception('The element already exists');
        }
        $this->elements[$element->getId()] = $element;
        $element->setStructure($this);
    } 
    
    public function removeElement($elementId) {
        if (empty($this->elements[$elementId])) {
            throw new Exception('There is no such an element');
        }
        $this->elements[$elementId]->unsetStructure();
        unset($this->elements[$elementId]);
    }
    
    public function addConnection(Connection $connection, $overrideIfExists = false) {
        if (!$connection) {
            throw new Exception('The connection is null');
        }
        if (!empty($this->connections[$connection->getId()]) && !$overrideIfExists) {
            throw new Exception('The connection already exists');
        }
        $this->connections[$connection->getId()] = $connection;
    }
    
    public function removeConnection($connectionId) {
        if (empty($this->connections[$connectionId])) {
            throw new Exception('There is no such a connection');
        }
        $this->connections[$connectionId];
        unset($this->elements[$connectionId]);
    }
    
    public function validate() {
        foreach ($this->elements as $element) {
            $this->checkElementType($element);
            $this->checkConnectionsWithElement($element);
        }
        foreach ($this->connections as $connection) {
            $this->checkElementsWithConnection($connection);
        }
    } 
    
    protected function checkElementType(Interfaces\Element $element) {
        if ($element instanceof Interfaces\Element\Node) {
            return true;
        }
        if ($element instanceof Interfaces\Element\EmptyField) {
            return true;
        }
        if ($element instanceof Interfaces\Element\EntryPoint) {
            return true;
        }
        throw new Exception('Invalid type of the element with id: '.$element->id());
    }
    
    protected function checkConnectionsWithElement(Interfaces\Element $element) {
        $inConnection = false;
        $hasConnectepPair = false;
        foreach ($this->connections as  $connection) {
            if (!$connection->hasConnected($element)) {
                continue;
            }
            $inConnection = true;
            $pair = $connection->getThrough($element);
            $hasConnectepPair = $this->checkConnectionsWithElement($pair);
            break;
        }
        if (!$inConnection) {
            throw new Exception('The following element has no connections: '.$element->id());
        }
        if (!$hasConnectepPair) {
            throw new Exception('The following element has no connected pair: '.$element->id());
        }
        return true;
    } 
    
    protected function checkElementsWithConnection($connection) {
        foreach ($this->elements as $element) {
            if ($connection->hasConnected($element)) {
                return true;
            }
        }
        throw new Exception('The following connection is not used: '.$connection->id());
    }
    
}
