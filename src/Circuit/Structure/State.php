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

namespace Circuit\Structure;

use Circuit\Structure;

/**
 * Description of State
 *
 * @author sobolevna
 */
class State extends Structure {
    
    protected $value;
    
    /**
     *
     * @var Structure 
     */
    protected $instance; 
    
    public function __construct($value = null, $id = '', $map = null, $from = '') {
        parent::__construct($id, $map);
        $this->value = $value instanceof State ? $value->value() : $value;
    }
    
        
    /**
     * 
     * @param mixed $id
     * @return string
     * @throws Exception
     */
    protected function setId($id) {
        if (is_string($id) || is_numeric($id)) {
            return $id;
        }
        elseif (!$id) {
            return self::class.'_'.time();
        }
        else {
            throw new Exception('Invalid id');
        }
    }
    
    public function value() {
        return $this->value;
    }
    
    public function getState() {
        return $this;
    }
    
    protected function toMap() {
        $map = [
            'id' => $this->id,
            'value' => \serialize($this->value),
            'instance' => self::class,
            'map' => $this->instance ? $this->instance->getMap(false) : ''
        ];
        return $map;
    }
    
    protected function fromMap($structureMap) {
        parent::fromMap($structureMap);
        $this->value = unserialize($this->map['value']);
    }
}
