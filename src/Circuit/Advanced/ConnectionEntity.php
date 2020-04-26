<?php 

namespace Circuit\Advanced; 

use Circuit\Basic\Entity;
use Circuit\Interfaces;
use Circuit\Traits;

class ConnectionEntity extends Entity implements Interfaces\Connection {
    
    use Traits\IsConnection;

    protected $description = 'An intermediate entity'; 

    public function __construct(array $elements, Interfaces\Core $core, Interfaces\Limitation $limitation, Interfaces\Particularity $particularity)
    {
        $this->elements = $elements;
        parent::__construct($core, $limitation, $particularity);
    }
}