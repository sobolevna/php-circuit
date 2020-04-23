<?php

namespace Circuit\Implementation\Basic;

use Circuit\Interfaces;
use Circuit\Interfaces\Element;
use Circuit\Traits\IsElement;

class StructuredElement extends StructuredEntity implements Interfaces\Element{

    use IsElement;

    protected $description = 'Entity that is a structure. Core is a Node, limitation is EntryPoint, particularity is EmtpyField';

    public function connect(Interfaces\Element $element) : Interfaces\Connection {
        $this->connections[] = new StructuredConnection($this, $element);
    }
}