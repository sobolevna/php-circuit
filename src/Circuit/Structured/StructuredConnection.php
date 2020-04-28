<?php

namespace Circuit\Structured; 

use Circuit\Interfaces;
use Circuit\Traits\{IsStructured, IsStructuredConnection};
use Circuit\Basic\Connection;
use Circuit\Exceptions\EntryPointConnectionException;

class StructuredConnection extends Connection implements Interfaces\Structure {

    use IsStructured, IsStructuredConnection;

    /**
     * @var string 
     */
    protected $description = 'Connection for structured elements';

    /**
     * @param StructuredElement[] $elements
     */
    public function __construct(array $elements) {
        parent::__construct($elements);
        $this->processElements($elements);
    }
    
}