<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Circuit\Interfaces\Structure;

use Circuit\Interfaces\Structure;

/**
 * A structure state. It can be any you wish
 * @author sobolevna
 */
interface State extends Structure{
    
    /**
     * The main function of state -- its value
     * @return mixed value of the object
     */
    public function value();    
    
}
