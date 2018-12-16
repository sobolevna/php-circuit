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

namespace Circuit\Framework\Structure\Element;

use Circuit\Framework\{Structure, Exception};
use Circuit\Framework\Structure\{Element, State};
use Circuit\Interfaces\Structure\Element\EmptyField as EmptyFieldInterface;

/**
 * Description of EmptyField
 *
 * @author sobolevna
 */
class EmptyField extends Element implements EmptyFieldInterface{
    
    /**
     *
     * @var Structure[] 
     */
    protected $entries = [];
    
    public function fill(Structure &$structure, array $structureEntryPoints = null, array $fieldEntryPoints = null, array $connectionInterfaceMap = null) {
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
    
    protected function addEntry(Structure &$structure) {
        $id = $structure->info()['id'];
        if (!empty($id)) {
            $this->entries[$id] = $structure;
        }
        else {
            $this->entries[] = $structure;
        }
    }
    
    /**
     * TODO: fillness count is awful
     * @return State
     */
    public function getState() {
        $isEmpty = count($this->entries) == 0;
        $isFull = count($this->entries) == count($this->internalEntryPoints);
        if(count($this->entries) == 0) {
            $fillness = -1;
        }
        elseif (count($this->entries) == count($this->internalEntryPoints)) {
            $fillness = 1;
        }
        else {
            $fillness = 0;
        }
        $this->state->fillness = $fillness;
        return parent::getState();
    }
}
