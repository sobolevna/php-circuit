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
use Circuit\Structure\Element\{EntryPoint, Node, EmptyField};
use Circuit\Structure\Exception\Process as Exception;

/**
 * Sequence is a process which has one start, one end and no forks
 *
 * @author sobolevna
 */
class Cycle extends Process{ 
    
    /**
     * An element from which the cycle accepts the state 
     * Usually it is an entry point, but it doesn't have to. 
     * If null or is not instance of EntryPoint, the process is invalid and can be used only in complex processes
     * @var Element
     */
    protected $startPoint; 
    
    /**
     * An element to which the cycle gives the result state 
     * The element takes the state even if it is not connected 
     * to the element on which the cycle is finished. 
     * If null or is not instance of EntryPoint, the process is invalid and can be used only in complex processes 
     * @var Element 
     */
    protected $endPoint; 
    
    /**
     *
     * @var Element[]
     */
    protected $cycleBody;
    
    /**
     *
     * @var callable 
     */
    protected $condition = null; 
    
    public $maxCycleCount = 10;
    
    public function __construct($id = '', array $map = null, $conditionCallback = null) {
        $this->condition = $conditionCallback;
        parent::__construct($id, $map);
    }

    /**
     * 
     * @param Element $element
     * @param string $id
     */
    public function append($element) {
        if (!($element instanceof Element)) {
            throw new Exception('A part of the cycle must be an element');
        }
        if ($element instanceof EntryPoint) {
            if (!$this->startPoint && !($element instanceof EntryPoint\Out)) {
                $this->startPoint = $element;
            }
            elseif (!$this->endPoint && !($element instanceof EntryPoint\In)) {
                $this->endPoint = $element;
            }
            else {
                throw new Exception('An entry point must be either a start or an end if the process');
            }
        }
        else {
            $this->appendToBody($element);
        }
        parent::append($element);
    } 
    
    public function appendToBody($element) {
        if (!($element instanceof Node) && !($element instanceof EmptyField)) {
            throw new Exception('The cycle body must consist of nodes or empty fields');
        }
        $this->cycleBody[] = $element;
    }
    
    /**
     * 
     * @return boolean
     * @throws Exception
     */
    public function validate($strict = false) {
        if (count($this->processElements) < 3) {
            throw Exception('A cycle must contain at least 3 elements');
        }
        if ($strict) {
            parent::validate();
        }        
        return true;
    }
    
    public function getFirstElement() {
        return $this->startPoint ? $this->startPoint : reset($this->cycleBody);
    }
    
    public function getLastElement() {
        return $this->endPoint ? $this->endPoint : reset($this->cycleBody);
    }
    
    /**
     * 
     * @param State $state
     * @return State
     */
    protected function run($state = null) {
        $currentState = $state;
        if ($this->startPoint) {
            $currentState = $this->startPoint->process($currentState);
        }
        $currentState->cycleCount = 0; 
        reset($this->cycleBody);
        while (!$this->checkExitCondition($currentState)) {
            $currentState = current($this->cycleBody)->process($currentState);
            if (!next($this->cycleBody)) {
                reset($this->cycleBody);
                $currentState->cycleCount++; 
            }
        }
        if ($this->endPoint) {
            $currentState = $this->endPoint->process($currentState);
        }
        return $currentState;
    }
    
    protected function checkExitCondition($state) {
        if ($this->condition) {
            return $this->condition($state);
        }
        else {
            return $state->cycleCount == $this->maxCycleCount;
        }
    }
}
