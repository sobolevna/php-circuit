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

namespace Circuit\Structure\Process;

use Circuit\Structure\{Process, Element, State};
use Circuit\Structure\Exception\Process as Exception;

/**
 * Sequence is a process which has one start, one end and no forks
 *
 * @author sobolevna
 */
class Sequence extends Process{
    
    /**
     *
     * @var Element[]  
     */
    protected $processElements = []; 
    
    /**
     *
     * @var boolean 
     */
    protected $isWhole;    
    
    /**
     *
     * @var boolean  
     */
    protected $isFinished;


    /**
     * 
     * @param string $id
     * @param array $map
     * @param boolean $isWhole
     */
    public function __construct($id = '', $map = [], $isWhole = true) {
        $this->isWhole = $isWhole;
        parent::__construct($id, $map);
    }    

    /**
     * 
     * @param Element $element
     * @param string $id
     */
    public function append($element) {
        if (!($element instanceof Element)) {
            throw new Exception('A part of the sequence must be an element');
        }
        if (!$this->isFinished && count($this->processElements) > 1 && $element instanceof Element\EntryPoint) {
            $this->isFinished = true;             
        }
        elseif ($this->isFinished) {
            throw new Exception('The sequence already has an exit entry point');
        }
        parent::append($element);
    } 
    
    /**
     * 
     * @return boolean
     * @throws Exception
     */
    public function validate($strict = false) {
        if (count($this->processElements) < 3) {
            throw Exception('A processElements must contain at least 3 elements');
        }
        if ($strict) {
            parent::validate();
        }        
        return true;
    }
    
    public function getFirstElement() {
        return reset($this->processElements);
    }
    
    public function getLastElement() {
        return end($this->processElements);
    }
    
    /**
     * 
     * @param State $state
     * @return State
     */
    protected function run($state = null) {
        $currentState = $state;
        for ($i = 0; $i < count($this->processElements); $i++) {
            $currentState = $this->processElements[$i]->process($currentState);
        }
        return $currentState;
    }
    
    
}
