<?php 

namespace Circuit\Implementation\Basic; 

use Circuit\Interfaces;
use Circuit\Traits\IsElement;

class Element extends Entity implements Interfaces\Element {

    use IsElement;

    protected $description = 'A basic element of any structure. No other properties';

    
}