<?php 

namespace Circuit\Traits; 

use Circuit\Interfaces\Element;

trait IsConnection {
    /**
     * @var Element[] $elements
     */
    protected $elements = [];
    
    /**
     * @return Element[]
     */
    public function getElements() : array {
        return $this->elements;
    }

    /**
     * @param Element[] $elements
     */
    protected function addConnectionToElements(array $elements) {
        $elementsToConnect = $elements;
        $currentElement = array_pop($elementsToConnect);
        if (empty($elementsToConnect)) {
            return;
        }
        foreach ($elementsToConnect as $elementToConnect) {
            $this->doConnect($currentElement, $elementToConnect);
        }
        $this->addConnectionToElements($elementsToConnect);
    }

    /**
     * @param Element $currentElement
     * @param Element $elementToConnect
     */
    protected function doConnect(Element $currentElement, Element $elementToConnect) {
        if (!\in_array($this, $elementToConnect->getConnections())) {
            $currentElement->addConnection($this);
        }        
        if (!\in_array($this, $currentElement->getConnections())) {
            $elementToConnect->addConnection($this);
        }
    }
}