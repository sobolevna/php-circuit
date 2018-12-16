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

use Circuit\Interfaces\Structure;
use Circuit\Interfaces\Structure\Element\{Node, EntryPoint, EmptyField};
use Circuit\Framework\Structure\Element\Node as FrameworkNode;
use Circuit\Framework\Structure\Element\EntryPoint as FrameworkEntryPoint;
use Circuit\Framework\Structure\Element\EmptyField as FrameworkEmptyField;

/**
 * Description of Builder
 *
 * @author sobolevna
 */
class Builder {
    
    public function buildNode($class) {
        if (is_object($class) && $class instanceof Node) {
            return $class;
        } 
        elseif (is_object($class) && $class instanceof Structure) {
            return $class->toNode();
        }
        elseif (is_object($class)) {
            return new Container($class);
        }
        elseif (class_exists($class)) {
            $object = new $class;
            if ($object instanceof Node) {
                return $object;
            }
        }
        elseif (!$class) {
            return new FrameworkNode();
        }
    }
    
    public function buildEntryPoint($class) {
        if (class_exists($class)) {
            $object = new $class;
            if (object instanceof EntryPoint) {
                return $object;
            }
        }
        elseif (!$class) {
            return new FrameworkEntryPoint();
        }
    }
    
    public function buildEmptyField($class) {
        if (class_exists($class)) {
            $object = new $class;
            if (object instanceof EmptyField) {
                return $object;
            }
        }
        elseif (!$class) {
            return new FrameworkEmptyField();
        }
    }
}
