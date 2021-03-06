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

namespace Circuit\Structure\Connection\Inner;

use Circuit\Structure\Element as StructureElement;
use Circuit\Structure\Connection\Inner;

/**
 * A basic connection class for connectionf elements
 *
 * @author sobolevna
 */
class Element extends Inner{
    
    /**
     * 
     * @param StructureElement $structure1
     * @param StructureElement $structure2
     * @return boolean
     */
    protected function checkConnectionTypes($structure1, $structure2) {
        if ($structure1 instanceof StructureElement\Node || $structure2 instanceof StructureElement\Node) {
            return true;
        }
        return false;
    }
}
