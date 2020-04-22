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

namespace Circuit\Structure\Builder;

use Circuit\Structure\Builder;
use Circuit\Structure\Exception\Builder as Exception;
use Circuit\Structure\Element\{
    Node,
    EntryPoint,
    EmptyField
};

/**
 * Description of Process
 *
 * @author sobolevna
 */
class Process {    
    
    protected $map = [];
    
    protected $processes = [];
    
    protected $instance;
    
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
        foreach ($this->processes as $key => $value) {
            if (!($structure->getById(end($value)) instanceof EntryPoint)) {
                unset($this->processes[$key]);
            }
        }
        $this->map['processes'] = array_values($this->processes);
        return $this->map;
    }
    
    /**
     * 
     * @param string $element
     * @return array
     */
    protected function getNextElements($element) {
        $connections = array_filter(
            $this->map['connections'], 
            function($connection) use($element) {
                return in_array($element, $connection['connected']);
            }
        );
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
            $connections
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
            if ($el == $from || in_array($el, $process)) {
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
        if ($element == 'ef1') {
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
            return $this->moveNext($el, $element, $processId);
        } else {
            return $this->moveFromFork(array_diff($next, [$from]), $element, $from, $processId);
        }
    }

}
