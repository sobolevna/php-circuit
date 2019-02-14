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
class Complex extends Process{
    
    /**
     *
     * @var Process[]  
     */
    protected $processElements = []; 
    
    /**
     * 
     * @param string $id
     * @param array $map
     * @param boolean $isWhole
     */
    public function __construct($id = '', $map = []) {
        parent::__construct($id, $map);
    }    

    /**
     * 
     * @param Element $element
     * @param string $id
     */
    public function append($process) {
        if (!($process instanceof Process)) {
            throw new Exception('A part of the process must be another process');
        }
        $thisLast = $this->getLastElement()->getLastElement();
        $processFirst = $process->getFirstElement();
        if ($thisLast instanceof Element\EntryPoint) {
            throw new Exception('You cannot append the process if the last one is finished with an entry point');
        }
        if ($processFirst instanceof Element\EntryPoint) {
            throw new Exception('You cannot append the process if the it is started with an entry point');
        }
        parent::append($process);
    } 
    
    /**
     * 
     * @return boolean
     * @throws Exception
     */
    public function validate($strict = false) {
        if (count($this->processElements) < 2) {
            throw Exception('A complex process must contain at least 2 processElements');
        }
        if ($strict) {
            parent::validate();        
        }
        return true;
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
    
    public function getFirstElement() {
        return reset($this->processElements)->getFirstElement();
    }
    
    public function getLastElement() {
        return end($this->processElements)->getLastElement();
    }
}
