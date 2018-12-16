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

namespace Circuit\Simple;

use Circuit\Simple\Exception;
use Circuit\Simple\Structure\{Builder,State,Element, Connection};
use Circuit\Simple\Structure\Element\{Node, EmptyField, EntryPoint};

/**
 * Description of Structure
 *
 * @author sobolevna
 */
class Structure {
    
    
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
    
    /**
     *
     * @var Connection[]
     */
    protected $connections = [];

    public function __construct($id = '', array $map = null) {
        $this->id = $id;
        $this->buildBuilder();
        if (!empty($map)) {
            $this->fromMap($map);
        }
        $this->state = new State();
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
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
       
    }
    
    public function entryPoints() {
        return $this->entryPoints;
    }
    
    public function getState() {
        return $this->state;
    }
    
    public function connect(Structure $connectWith, array $connectionMap = null, $id = '') {
        try {
            return new Connection($id, $this, $connectWith, $connectionMap);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return null;
        }
    }
    
    public function info() {
        return ['id'=> $this->id];
    }    
    
    public function append(&$element, $id = '') {
        $elementId = $id ? $id : $element->info()['id'];
        if ($element instanceof EmptyField) {
            $this->emptyFields[$elementId] = $element;
        } 
        elseif ($element instanceof EntryPoint) {
            $this->entryPoints[$elementId] = $element;
        }
        elseif ($element instanceof Node) {
            $this->nodes[$elementId] = $element;
        }
        else {
            throw new Exception('You can append to a structure onle specified elements -- Nodes, Entry points or Empty Fields');
        }
    }
    
}
