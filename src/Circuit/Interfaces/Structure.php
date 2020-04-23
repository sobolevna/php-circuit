<?php 

namespace Circuit\Interfaces;

interface Structure {

    /**
     * @return Elements[] 
     */
    public function getStructureElements() : array;

    /**
     * @return Connections[] 
     */
    public function getStructureConnections() : array;
    
}