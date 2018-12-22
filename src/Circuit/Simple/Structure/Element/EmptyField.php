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

namespace Circuit\Simple\Structure\Element;

use Circuit\Simple\{Structure, Exception};
use Circuit\Simple\Structure\{Element, State};

/**
 * Description of EmptyField
 *
 * @author sobolevna
 */
class EmptyField extends Element {
    
    /**
     *
     * @var Structure[] 
     */
    protected $contents = [];
    
    public function fill(&$structure, array $structureEntryPoints = null, array $fieldEntryPoints = null, array $connectionInterfaceMap = null) {
        try {
            $sourceEP = empty($fieldEntryPoints) ? $this->internalEntryPoints() : $fieldEntryPoints;
            $targetEP = empty($structureEntryPoints) ? $structure->entryPoints() : $structureEntryPoints; 
            $map = $this->connectionInterfaceMap($fieldEntryPoints, $connectionInterfaceMap);
            foreach ($sourceEP as $key => $ep) {
                $ret = $ep->connectExternal($targetEP[$key], $map[$key]);
                if (!$ret) {
                    return false;
                }
            }
            $this->addEntry($structure);
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
    
    protected function addContent(&$structure) {
        $id = $structure->info()['id'];
        if (!empty($id)) {
            $this->contents[$id] = $structure;
        }
        else {
            $this->contents[] = $structure;
        }
    }
    
    /**
     * TODO: fillness count is awful
     * @return State
     */
    public function getState() {
        $isEmpty = count($this->contents) == 0;
        $isFull = count($this->contents) == count($this->internalEntryPoints);
        if(count($this->contents) == 0) {
            $fillness = -1;
        }
        elseif (count($this->contents) == count($this->internalEntryPoints)) {
            $fillness = 1;
        }
        else {
            $fillness = 0;
        }
        $this->state->fillness = $fillness;
        return parent::getState();
    }
}
