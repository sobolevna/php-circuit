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

    public function checkAndGetStructureMap($structureMap) {
        if (is_string($structureMap)) {
            $map = json_decode($structureMap, true);
            if (!$map) {
                throw new Exception('The given string is not a valid JSON');
            }
        } elseif (!is_array($structureMap)) {
            throw new Exception('A map must be either an array or JSON string');
        } else {
            $map = $structureMap;
        }
        if (!array_key_exists('elements', $structureMap) && !array_key_exists('nodes', $map['elements']) && !array_key_exists('emptyFields', $map['elements']) && !array_key_exists('entryPoints', $map['elements'])) {
            throw new Exception('The map has no elements');
        }
        if (empty($map['connections'])) {
            throw new Exception('There should be at least one connection');
        }
        return $map;
    }

    public function checkAndGetElementMap($elementMap, $type = Node::class) {
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

    /**
     * Алгоритм примерно такой:
     * пройтись по каждой точке входа, кроме откровенно выходных
     * От каждой из них проложить все возможные маршруты к другим точкам входа, кроме откровенно входных
     * Если нашёл развилку, создаётся дополнительный маршрут для каждого возможного пути
     * Если зашёл в тупик, идти обратно (до точки входа или развилки)
     * Убрать возникшие дубли 
     * @param Structure $structure A structure to build a process map
     * @return array A map with all possible processes
     */
    public function buildProcessMap($structure) {
        $this->instance = $structure;
        $this->map = $structure->getMap();
        foreach ($this->map['elements']['entryPoints'] as $point) {
            if ($structure->getById($point['id']) instanceof EntryPoint\Out) {
                continue;
            }
            $this->processes[] = [];
            end($this->processes);
            $processId = key($this->processes);
            $this->moveNext($point['id'], '', $processId);
        }
        $this->map['processes'] = $this->processes;
        return $this->map;
    }
    
    /**
     * 
     * @param string $element
     * @return array
     */
    protected function getNextElements($element) {
        return array_map(
            function($connection) use($element) {
                return current(
                        array_filter(
                            $connection['connected'], 
                            function($connected) use($element) {
                                return $connected != $element;
                            }
                        )
                    );
            }, 
            array_filter(
                $this->map['connections'], 
                function($connection) use($element) {
                    return in_array($element, $connection['connected']);
                }
            )
        );
    }
    
    /**
     * 
     * @param array $next
     * @param string $element
     * @param string $from
     * @param number $processId
     * @return array
     */
    protected function moveFromFork($next, $element, $from = '', $processId = null) {
        $process = $this->processes[$processId];
        foreach ($next as $el) {
            if ($el == $from) {
                continue;
            }
            if (end($this->processes[$processId]) && current($this->processes[$processId]) != $element) {
                $this->processes[] = $process;
                end($this->processes);
                $processId = key($this->processes);
            }
            $this->moveNext($el, $element, $processId);
        }
        return $this->processes[$processId];
    }

    /**
     * Iterates over elements to build a process map
     * @param string $element
     * @param string $from
     * @param number $processId
     * @return array
     */
    protected function moveNext($element, $from = '', $processId = null) {
        $next = $this->getNextElements($element);
        $this->processes[$processId][] = $element;
        $instance = $this->instance->getById($element);
        if ($element == 'ep2') {
            //var_dump('ID: '.$element.'; from: '.$from.'; processId: '.$processId);
        }
        if ($instance instanceof EntryPoint\In && $from) {
            array_pop(сurrent($this->processes[$processId]));
            return $this->moveNext($from, $element, $processId);
        } elseif ($instance instanceof EntryPoint && $from) {
            return $this->processes[$processId];
        } elseif (count($next) == 0) {
            return $this->processes[$processId];
        } elseif (count($next) == 1) {
            return $this->moveNext(current($next), $element, $processId);
        } elseif (count($next) == 2) {
            $el = current(array_diff($next, [$from]));
            if ($el == 'ep2') {
                //var_dump($element);
            }
            return $this->moveNext($el, $element, $processId);
        } else {
            return $this->moveFromFork(array_diff($next, [$from]), $element, $from, $processId);
        }
    }

}
