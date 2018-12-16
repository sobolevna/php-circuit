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

use Circuit\Simple\Exception;
use Circuit\Simple\Structure;
use Circuit\Simple\Structure\Element\EntryPoint;


/**
 * Description of Connection
 *
 * @author sobolevna
 */
class Connection extends Structure {
    
    /**
     *
     * @var Structure[] 
     */
    protected $connected;
    
    /**
     * If the connection is between 2 empty entry points
     * @var bool 
     */
    protected $isStraight = false;

    public function __construct($id, Structure $structure1, Structure $structure2) {
        if ($this->checkConnectionTypes($structure1,$structure2)) {
            $connectionId = !$id ? $structure1->info()['id'].'__'.$structure2->info()['id'] : $id;
            $this->connected[] = $structure1;
            $this->connected[] = $structure2;
            if ($structure1->element() instanceof EntryPoint && $structure1->element()->isElementary() && $structure2->element() instanceof EntryPoint && $structure2->element()->isElementary()) {
                $this->isStraight = true;
            }
            parent::__construct($connectionId, null);
        }
        else {
            throw new Exception('Invalid types for connection.');
        }
    }
    
    public function info() {
        $info = parent::info();
        $info['isStraight'] = $this->isStraight;
    } 
    
    /**
     * Nodes can be connected to anything. 
     * Entry points can be connected internally to nodes, 
     * externally to other entry points.
     * Empty fields can be connected only to nodes.
     * @param Structure $structure1
     * @param Structure $structure2
     * @return boolean
     */
    protected function checkConnectionTypes($structure1, $structure2) {
        if ($structure1 instanceof Element\Node || $structure2 instanceof Element\Node) {
            return true;
        }
        elseif ($structure1 instanceof Element\EntryPoint && $structure2 instanceof Element\EntryPoint) {
            return true;
        }
        return false;
    }
}
