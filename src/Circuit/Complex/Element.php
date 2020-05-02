<?php

namespace Circuit\Complex;

use Circuit\Interfaces;
use Circuit\Traits\IsElement;

class Element extends Entity implements Interfaces\Element{

    use IsElement;

    protected $description = 'Element based on complex entity';

    protected $connectionClass = Connection::class;
}