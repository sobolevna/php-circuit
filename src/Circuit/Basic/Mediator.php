<?php

namespace Circuit\Basic;

use Circuit\{Interfaces, Traits};

class Mediator extends Element implements Interfaces\Connection {

    use Traits\IsConnection;

    public function __construct(Interfaces\Core $core, Interfaces\Limitation $limitation, Interfaces\Particularity $particularity, array $elements = []) {
        parent::__construct($core, $limitation, $particularity);
        if (!empty($elements)) {
            $this->elements = $elements;
            $this->addConnectionToElements($elements);
        }
    }
}