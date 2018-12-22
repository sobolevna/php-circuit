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

namespace Circuit\Simple\Structure;

use Circuit\Simple\Structure;
use Circuit\Interfaces\Structure\State as StateInterface;

/**
 * Description of State
 *
 * @author sobolevna
 */
class State extends Structure{
    
    protected $value;
    
    /**
     *
     * @var Structure 
     */
    protected $instance;

    public function __construct($value = null, $id = '', $instance = null) {
        $this->id = $this->setId($id);
        $this->value = $value;
        $this->instance = $instance instanceof Structure ? $instance : null;
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
    
    public function getValue() {
        return $this->value;
    }
    
    public function getState() {
        return $this;
    }
    
    public function toMap($toJson = false) {
        $map = [
            'id' => $this->id,
            'value' => $this->value,
            'instance' => self::class,
            'map' => $this->instance ? $this->instance->toMap(false) : ''
        ];
        return $toJson ? json_encode($map) : $map;
    }
}
