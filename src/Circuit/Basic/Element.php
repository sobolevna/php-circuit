<?php 

namespace Circuit\Basic; 

use Circuit\Interfaces;
use Circuit\Traits\IsElement;

class Element extends Entity implements Interfaces\Element {

    use IsElement;

    protected $description = 'A basic element of any structure. No other properties';

    protected $connectionClass = Connection::class;
}