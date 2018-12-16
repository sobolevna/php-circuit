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

namespace Circuit\Simple\Structure\Element;

use Circuit\Simple\Exception;
use Circuit\Simple\Structure\{Connection, Element};

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
    
    /**
     *
     * @var Connection 
     */
    protected $externalConnection;

    public function __construct($instance = null, array $map = null, $connectionInterface = Connection::class) {
        parent::__construct($instance, $map);
        $this->connectionInterface = $connectionInterface;
    }
    
    public function connectExternal(Element\EntryPoint &$target, string $connectionInterface = Connection::class, $id = '') {
        if ($this->isElementary()) {
            $class = $this->connectionInterface;
            return new $class($id, $this, $target);
        }
        throw new Exception\Connection('Error in connecting entry points');
        
    }
    
    public function connectInternal(Element\Node $target, string $connectionInterface = Connection::class, $id = '') {
        ;
    }
    
    public function sendResponse(\Circuit\Interfaces\Structure\State $state) {
        ;
    }
    
    public function connect(\Circuit\Simple\Structure $connectWith, array $connectionMap = null, $id = '') {
        if ($connectWith instanceof Element\EntryPoint) {
            return $this->connectExternal($connectWith, $this->connectionInterface, $id);
        }
        elseif ($connectWith instanceof Element\EmptyField) {
            return $this->connectInternal($connectWith, $this->connectionInterface, $id);
        }
        throw new Exception('Entry point must be connected euther to another entry point or a node');
    }
}
