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

use Circuit\Interfaces;

/**
 * Description of Connection
 *
 * @author sobolevna
 */
class Connection implements Interfaces\Connection{
    
    /**
     *
     * @var Element[] 
     */
    protected $connected = [];
    
    /**
     * 
     * @param Element[] $connected
     * @param string $id
     * @throws Exception
     */
    public function __construct(array $connected, $id) {
        $this->id = $id;
        foreach ($connected as $element) {
            if(!($element instanceof Interfaces\Element)) {
                throw new Exception('A connection is used to connect elements');
            }
            $element->bindConnection($this);
        }
        $this->connected = $connected;
    }
    
    public function id() {
        return $this->id;
    }

    public function getThrough(Interfaces\Element $element): Interfaces\Element {
        foreach ($this->connected as $connectedElement) {
            if ($connectedElement == $element) {
                continue;
            }
            return $connectedElement;
        }
    }
    
    public function hasConnected(Interfaces\Element $element) {
        if (in_array($element, $this->connected)) {
            return true;
        }
        return false;
    }

}
