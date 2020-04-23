<?php 

namespace Circuit\Basic; 

use Circuit\Interfaces;

class StructureBuilder {

    protected $elements;

    protected $connections;

    public function setFirstElement(Interfaces\Element $element) : StructureBuilder {
        $this->firstElement = $element;
        return $this;
    }

    public function build() : Interfaces\Structure {
        if (!$this->firstElement) {
            throw new BuilderException('To build a structure you need the first element');
        }
        
        $this->walkThroughElements($this->firstElement);
        
        return new Structure($this->elements, $this->connections);
    }

    protected function walkThroughElements($element) {
        if (in_array($element, $this->elements)) {
            return;
        }
        $this->elements[] = $element;
        $elementConnections = $element->getConnections();
        $this->connections = array_merge($this->connections, $elementConnections);
        $connectedElements = []; 
        foreach ($elementConnections as $connection) {
            $currentConnectionElements = $connection->getElements();
            $connectedElements = array_merge($connectedElements, array_splice($currentConnectionElements, array_search($element, $currentConnectionElements)));
        }
        $elementsToAdd = array_diff($connectedElements, $this->elements);
        if (empty($elementsToAdd)) {
            return;
        }
        foreach ($elementsToAdd as $item) {
            $this->walkThroughElements($item);
        }
    }
}