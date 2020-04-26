<?php

namespace Circuit\Basic;

use Circuit\Interfaces;
use Circuit\Interfaces\Element;
use Circuit\Traits\IsElement;

class ComplexElement extends ComplexEntity implements Interfaces\Element{

    use IsElement;

    protected $description = 'Element based on complex entity';

    protected $connectionClass = ComplexConnection::class;
}