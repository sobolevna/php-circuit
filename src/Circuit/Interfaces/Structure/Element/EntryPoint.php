<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Circuit\Interfaces\Structure\Element;

use Circuit\Interfaces\Structure\Element;
use Circuit\Interfaces\Structure\Element\Node;
use Circuit\Interfaces\Structure\{State, Connection}; 


/**
 *
 * @author sobolevna
 */
interface EntryPoint extends Element{
    
    public function connectExternal(EntryPoint &$target, string $connectionInterface = Connection::class, $id = '') : Connection; 
    
    public function connectInternal(Node &$target, string $connectionInterface = Connection::class, $id = '') : Connection; 
    
    public function sendResponse(State $state): bool;
    
}
