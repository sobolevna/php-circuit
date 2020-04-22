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

namespace Circuit\Implementation\Simple;

use Circuit\Interfaces\{Element, Connection};

/**
 * Description of Element
 *
 * @author sobolevna
 */
class Element implements Element{
    
    protected $id;
    
    protected $value; 
    
    protected $elementConnections = [];
    
    protected $structure;
    
    public function __construct($id, $value) {
        $this->id = $id;
        $this->value = $value;
    }
        
    public function bindConnection(Connection $connection) {
        $this->elementConnections[] = $connection;
    }
    
    public function id() {
        return $this->id;
    }
    
    public function value() {
        return $this->value;
    }
    
    public function setStructure(Structure $structure) {
        $this->structure = $structure;
    }
    
    public function unsetStructure() {
        unset($this->structure);
    }
    
    public function structure() {
        return $this->structure;
    }

}
