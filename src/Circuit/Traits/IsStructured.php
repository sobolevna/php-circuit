<?php 

namespace Circuit\Traits; 

trait IsStructured {
    /**
     * @propery Element[] $elements
     */
    protected $structureElements = [];
    /**
     * @property Connection[]
     */
    protected $structureConnections = []; 

    /**
     * @return Element[]
     */
    public function getStructureElements() : array {
        return $this->structureElements;
    }

    /**
     * @return Connection[]
     */
    public function getStructureConnections() : array {
        return $this->structureConnections;
    }
}