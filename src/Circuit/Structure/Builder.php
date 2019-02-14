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
use Circuit\Structure\Exception\Builder as Exception;
use Circuit\Structure\Element\{
    Node,
    EntryPoint,
    EmptyField
};

/**
 * Description of Builder
 *
 * @author sobolevna
 */
class Builder {

    protected $map = [];
    
    protected $processes = [];
    
    protected $instance;

    public function node($class = Node::class, $id = '', $map = null) {
        if (is_object($class) && $class instanceof Node) {
            return $class;
        } elseif (is_object($class) && $class instanceof Structure) {
            return $class->element()->toNode();
        } elseif (is_object($class)) {
            return (new Container($class))->element()->toNode();
        } elseif (class_exists($class) && ($class == Node::class || in_array(Node::class, class_parents($class)))) {
            return new $class($id, $map);
        }
        throw new Exception('Invalid settings for building a node');
    }

    public function entryPoint($class = EntryPoint::class, $id = '', $map = null) {
        if (class_exists($class) && ($class == EntryPoint::class || in_array(EntryPoint::class, class_parents($class)))) {
            return new $class($id, $map);
        }
        throw new Exception('Invalid settings for building an entry point');
    }

    public function emptyField($class = EmptyField::class, $id = '', $map = null, $contents = null) {
        if (class_exists($class) && ($class == EmptyField::class || in_array(EmptyField::class, class_parents($class)))) {
            return new $class($id, $map, $contents);
        }
        throw new Exception('Invalid settings for building an empty field');
    }

    public function connection($structure1, $structure2, array $connectionMap = null, $id = '', $type = '') {
        $class = $this->getConnectionClass($type);
        if (!class_exists($class)) {
            throw new Exception\Connection('Invalid connection class');
        }
        return new $class($id, $structure1, $structure2, $connectionMap);
    }

    protected function getConnectionClass($type = '') {
        $typeMap = [
            'element' => Connection\Inner\Element\Simple::class,
            'inter' => Connection\Outer\Inter::class,
            'trans' => Connection\Outer\Trans\Simple::class
        ];
        if (!empty($typeMap[$type])) {
            return $typeMap[$type];
        }
        return Connection::class;
    }

    public function fromMap($structureMap, $type) {
        $classes = [
            'node' => Node::class,
            'emptyField' => EmptyField::class,
            'entryPoint' => EntryPoint::class
        ];
        $elementType = !empty($classes[$type]) ? $classes[$type] : $type;
        $map = $this->checkAndGetElementMap($structureMap, $elementType);
        return $this->{$type}($map['instance'], $map['id'], !empty($map['map']) ? $map['map'] : null, !empty($map['contents']) ? $map['contents'] : null);
    }

    protected function checkAndGetElementMap($elementMap, $type = Node::class) {
        if (is_string($elementMap)) {
            $map = json_decode($elementMap, true);
            if (!$map) {
                throw new Exception('The given string is not a valid JSON');
            }
        } elseif (!is_array($elementMap)) {
            throw new Exception('A map must be either an array or JSON string');
        } else {
            $map = $elementMap;
        }
        if (empty($map['id']) && empty($map['instance']) && empty($map['map'])) {
            throw new Exception('The map has no valid fields');
        }
        if (class_exists($map['instance']) || $map['instance'] == $type || in_array($type, class_parents($map['instance']))) {
            return $map;
        } else {
            throw new Exception('Invalid map instance');
        }
    }

    
}
