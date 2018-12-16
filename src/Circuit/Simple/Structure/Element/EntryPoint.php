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

namespace Circuit\Framework\Structure\Element;

use Circuit\Framework\Exception;
use Circuit\Framework\Structure\{Connection, Element};
use Circuit\Interfaces\Structure\Connection as ConnectionInterface;
use Circuit\Interfaces\Structure\Element\EntryPoint as EntryPointInterface;

/**
 * Description of EntryPoint
 *
 * @author sobolevna
 */
class EntryPoint extends Element implements EntryPointInterface{
    
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
    
    
}
