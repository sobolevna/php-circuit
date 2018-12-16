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

namespace Circuit\Framework\Structure;

use Circuit\Framework\Structure;
use Circuit\Framework\Structure\Element\EntryPoint;
use Circuit\Interfaces\Structure\Connection as ConnectionInterface;

/**
 * Description of Connection
 *
 * @author sobolevna
 */
class Connection extends Structure implements ConnectionInterface{
    
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
        $connectionId = !$id ? $structure1->info()['id'].'__'.$structure2->info()['id'] : $id;
        $this->connected[] = $structure1;
        $this->connected[] = $structure2;
        if ($structure1->element() instanceof EntryPoint && $structure1->element()->isElementary() && $structure2->element() instanceof EntryPoint && $structure2->element()->isElementary()) {
            $this->isStraight = true;
        }
        parent::__construct($connectionId, null);
    }
    
    public function info() {
        $info = parent::info();
        $info['isStraight'] = $this->isStraight;
    }
}
