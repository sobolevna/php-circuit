<?php 

namespace Circuit\Basic; 

use Circuit\Interfaces;

class StructureBuilder {

    /**
     * @var Interfaces\Element[] $elements
     */
    protected $elements = [];

    /**
     * @var Interfaces\Connections[] $connections
     */
    protected $connections = [];

    /**
     * @var Interfaces\Element $firstElement
     */
    protected $firstElement;

    /**
     * @param Interfaces\Element $element
     * @return StructureBuilder
     */
    public function setFirstElement(Interfaces\Element $element) : StructureBuilder {
        $this->firstElement = $element;
        return $this;
    }

    /**
     * @return Interfaces\Structure
     */
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
        $this->connections = $this->arrayMerge($this->connections, $elementConnections);
        $connectedElements = []; 
        foreach ($elementConnections as $connection) {
            $currentConnectionElements = $connection->getElements();
            $otherElements = $this->getOtherElements($element, $currentConnectionElements);
            $connectedElements = $this->arrayMerge($connectedElements, $otherElements);
        }
        $elementsToAdd = array_udiff($connectedElements, $this->elements, function($connectedElement, $existingElement){
            return $connectedElement == $existingElement ? 0 : -1;
        });
        if (empty($elementsToAdd)) {
            return;
        }
        foreach ($elementsToAdd as $item) {
            $this->walkThroughElements($item);
        }
    }

    protected function getOtherElements($element, $elementArray) {
        $result = [];
        foreach ($elementArray as $item) {
            if ($element != $item) {
                $result[] = $item;
            }
        }
        return $result;
    }

    protected function arrayMerge($array1, $array2) {
        $result = $array1;
        foreach($array2 as $item) {
            if (!in_array($item, $result)) {
                $result[] = $item;
            }
        }
        return $result;
    }
}