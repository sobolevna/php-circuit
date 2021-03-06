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

namespace Circuit\Structure\Element\EntryPoint;

use Circuit\Structure\Element\EntryPoint;

/**
 * Description of In
 *
 * @author sobolevna
 */
class Out extends EntryPoint {
        
    protected function getInfo() {
        return [
            'id' => $this->id,
            'connectionInterface' => $this->connectionInterface,
            'stateType' => $this->stateType,
            'isIn' => false,
            'isOut' => true
        ];
    }
}
