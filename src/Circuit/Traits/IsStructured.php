<?php 

namespace Circuit\Traits; 

use Circuit\Interfaces\{Element, Connection};

trait IsStructured {
    /**
     * @propery Element[] $elements
     */
    protected $structureElements = [];
    /**
     * @var Connection[]
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