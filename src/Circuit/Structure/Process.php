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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Circuit\Structure;

use Circuit\Structure;
use Circuit\Structure\Element;
use Circuit\Structure\Exception\Process as Exception;

/**
 * Description of Process
 *
 * @author sobolevna
 */
abstract class Process extends Structure{
    
    /**
     *
     * @var Structure[]
     */
    protected $processElements = [];
    
    /**
     * 
     * @param Process|Element $structure
     * @param string $id
     */
    public function append(Structure $structure) {
        if (!($structure instanceof Element || $structure instanceof Process)) {
            throw new Exception('A part of the process must be either an element or a process');
        }
        $id = $structure->info()['id'];
        if (!$this->getById($id) && $structure instanceof Element) {
            if ($structure instanceof EmptyField) {
                $this->emptyFields[$id] = $structure;
            } 
            elseif ($structure instanceof EntryPoint) {
                $this->entryPoints[$id] = $structure;
            }
            elseif ($structure instanceof Node) {
                $this->nodes[$id] = $structure;
            }
            else {
                throw new Exception('You can append to a structure only specified elements -- Nodes, Entry points or Empty Fields');
            }
        }
        $this->processElements[] = $structure;
    } 
    
    /**
     * 
     * @param State $state
     * @return State
     */
    public function process($state = null) {
        return $this->run($state);
    }
    
    /**
     * 
     * @return boolean
     * @throws Exception
     */
    public function validate() {
        if (!($this->getFirstElement()->info('isIn'))) {
            throw Exception('The first element must be an EntryPoint to enter');
        }    
        if (!($this->getLastElement()->info('isOut'))) {
            throw Exception('The last element must be an EntryPoint to exit');
        }
        return true;
    }


    abstract protected function run($state = null);

    /**
     * @return Element The first element of the process
     */
    abstract public function getFirstElement();
    
    /**
     * @return Element The last element of the process
     */
    abstract public function getLastElement();
}
