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

namespace Circuit\Framework; 

use Circuit\Interfaces; 
use Circuit\Framework\Structure\{Builder,State,Element};
use Circuit\Framework\Structure\Element\{Node, EmptyField, EntryPoint, MockEntryPoint};

/**
 * Description of Structure
 *
 * @author sobolevna
 */
class Structure implements Interfaces\Structure{
    
    protected $id;
    
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

    public function __construct($id = '', array $map = null) {
        $this->id = $id;
        $this->buildBuilder();
        if (!empty($map)) {
            $this->fromMap($map);
        }
        
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
    public function builder() : Builder {
        return $this->builder;
    }
    
    protected function fromMap(array $map) {
        
    } 
    
    public function process(State $state = null) {
        return new State(['oldValue' => $state->value(), 'newValue'=> date()]);
    }
    
    public function element() : Element {
        if($this instanceof Element) {
            return $this;
        }
        if (!$this->element || !($this->element instanceof Element)) {
            $this->element = new Element($this);
        }
        return $this->element;
    }
    
    /**
     * Inserts a structure to an empty field
     * @param EmptyField $emptyField
     * @param EntryPoint[] $sourceEntryPoints
     * @param EntryPoint[] $targetEntryPoints
     * @param array $connectionInterfaceMap 
     * @return bool
     */
    public function insertTo(EmptyField $emptyField, array $sourceEntryPoints = null, array $targetEntryPoints = null, array $connectionInterfaceMap = null) {
        try {
            $sourceEP = empty($sourceEntryPoints) ? $this->entryPoints() : $sourceEntryPoints;
            $targetEP = empty($targetEntryPoints) ? $emptyField->internalEntryPoints() : $targetEntryPoints; 
            $map = $this->connectionInterfaceMap($sourceEntryPoints, $connectionInterfaceMap);
            foreach ($sourceEP as $key => $ep) {
                $ret = $ep->connectExternal($targetEP[$key], $map[$key]);
                if (!$ret) {
                    return false;
                }
            }
            return true;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
       
    }
    
    public function entryPoints() {
        if (!empty($this->entryPoints)) {
            return $this->entryPoints;
        }
        $this->entryPoints[] = new MockEntryPoint('MockEntryPoint_'.$this->id);
        return $this->entryPoints;
    }
    
    protected function connectionInterfaceMap(array $sourceEntryPoints, array $connectionInterfaceMap= []) {
        $ret = [];
        foreach ($sourceEntryPoints as $k => $v) {
            if (empty($connectionInterfaceMap[$k])) {
                $ret[$k] = Connection::class;
            }
            elseif (in_array(Interfaces\Structure\Connection::class, class_implements($connectionInterfaceMap[$k]))){
                $ret[$k] = $connectionInterfaceMap[$k];
            }
            else {                
                throw new \Exception('Invalid connection interface');
            }
            return $ret;
        }
    }
    
    public function getState() {
        return $this->state;
    }
    
    public function connect(Structure $connectWith, array $sourceEntryPoints = null, array $targetEntryPoints = null, array $connectionInterfaceMap = null) : Connection {
        try {
            $sourceEP = empty($sourceEntryPoints) ? $this->entryPoints() : $sourceEntryPoints;
            $targetEP = empty($targetEntryPoints) ? $connectWith->entryPoints() : $targetEntryPoints; 
            $map = $this->connectionInterfaceMap($sourceEntryPoints, $connectionInterfaceMap);
            foreach ($sourceEP as $key => $ep) {
                $ret = $ep->connectExternal($targetEP[$key], $map[$key]);
                if (!$ret) {
                    return false;
                }
            }
            return true;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
    
    public function info() {
        return ['id'=> $this->id];
    }
    
}
