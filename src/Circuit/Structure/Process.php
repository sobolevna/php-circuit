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

namespace Circuit\Structure;

use Circuit\Structure;
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
     * @param Structure $structure
     * @param string $id
     */
    public function append($structure) {
        if (!($structure instanceof Structure)) {
            throw new Exception('A part of the process must be a structure');
        }
        if (!$this->getById($structure->info()['id']) && $structure instanceof Element) {
            parent::append($structure);
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
        $first = $this->getFirstElement();
        if (!($first instanceof Element\EntryPoint && !($first instanceof Element\EntryPoint\Out))) {
            throw Exception('The first element must be an EntryPoint to enter');
        }    
        $last = $this->getLastElement();
        if (!($last instanceof Element\EntryPoint && !($last instanceof Element\EntryPoint\In))) {
            throw Exception('The last element must be an EntryPoint to exit');
        }
        return true;
    }


    abstract protected function run($state = null);

    abstract public function getFirstElement();
    
    abstract public function getLastElement();
}
