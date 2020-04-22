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

namespace Circuit\Structure\Connection\Inner\Element;

//use Circuit\Structure\Element;
use Circuit\Structure\Connection\Inner\Element as ElementConnection;

/**
 * This connection is only between simple elements -- without any lesser elements within them
 *
 * @author sobolevna
 */
class Simple extends ElementConnection {
    
    /**
     * 
     * @param Element $structure1
     * @param Element $structure2
     * @return boolean
     */
    protected function checkConnectionTypes($structure1, $structure2) {
        if (!parent::checkConnectionTypes($structure1, $structure2)) {
            return false;
        }
        elseif ($structure1->isSimple() && $structure2->isSimple()) {
            return true;
        }
        return false;
    }
}
