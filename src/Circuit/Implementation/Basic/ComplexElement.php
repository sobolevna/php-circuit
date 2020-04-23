<?php

namespace Circuit\Implementation\Basic;

use Circuit\Interfaces;
use Circuit\Interfaces\Element;
use Circuit\Traits\IsElement;

class ComplexElement extends ComplexEntity implements Interfaces\Element{

    use IsElement;

    protected $description = 'Element based on complex entity';

    public function connect(Interfaces\Element $element) : Interfaces\Connection {
        $this->connections[] = new ComplexConnection($this, $element);
    }
}