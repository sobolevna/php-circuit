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

namespace Circuit\Structure\Element;

use Circuit\Structure\Exception\Element as Exception;
use Circuit\Structure\{Connection, Element, State};

/**
 * Description of EntryPoint
 *
 * @author sobolevna
 */
class EntryPoint extends Element {
    
    /**
     *
     * @var Connection 
     */
    protected $connectionInterface;
    
    protected $stateType;

    /**
     *
     * @var Connection 
     */
    protected $transConnection;

    public function __construct($instance = null, array $map = null, $connectionInterface = Connection::class, $stateType = State::class) {
        parent::__construct($instance, $map);
        $this->connectionInterface = $connectionInterface;
        $this->stateType = $stateType;
    }
    
    public function connectExternal(&$target, string $connectionInterface = Connection::class, $id = '') {
        if ($this->isElementary()) {
            $class = $this->connectionInterface;
            return new $class($id, $this, $target);
        }
        throw new Exception\Connection('Error in connecting entry points');
        
    }
    
    public function connectInternal(Element\Node $target, string $connectionInterface = Connection::class, $id = '') {
        ;
    }
    
    public function connect( $connectWith, array $connectionMap = null, $id = '') {
        if ($connectWith instanceof Element\EntryPoint) {
            return $this->connectExternal($connectWith, $this->connectionInterface, $id);
        }
        elseif ($connectWith instanceof Element\Node) {
            return $this->connectInternal($connectWith, $this->connectionInterface, $id);
        }
        throw new Exception('Entry point must be connected euther to another entry point or a node');
    }
    
    public function process($state = null) {
        if (!$this->checkStateType($state)) {
            throw new Exception('Invalid state type');
        }
        return parent::process($state);
    }
    
    public function checkStateType($state) {
        if (!$state || $state instanceof $this->stateType) {
            return true;
        }
        return false;
    }
    
    public function toNode() {
        if (!$this->transConnection) {
            return parent::toNode();
        }
        throw new Exception('Entry point with a trans-connection cannot be converted');
    }
    
    public function toEmptyField() {
        if (!$this->transConnection) {
            return parent::toEmptyField();
        }
        throw new Exception('Entry point with a trans-connection cannot be converted');
    }
    
    public function toEntryPoint() {
        return $this;
    }
    
    protected function getInfo() {
        return [
            'id' => $this->id,
            'connectionInterface' => $this->connectionInterface,
            'stateType' => $this->stateType,
            'isIn' => true,
            'isOut' => true
        ];
    }
}
