<?php

namespace Circuit\Structured;

use Circuit\Interfaces;
use Circuit\Basic;
use Circuit\Traits\IsStructured;

class Entity extends Basic\Entity implements Interfaces\Structure, Interfaces\Entity {

    use IsStructured;

    protected $description = 'Entity that is a structure. Core is a Node, limitation is EntryPoint, particularity is EmtpyField';

    public function __construct(Interfaces\Node $core, Interfaces\EntryPoint $limitation, Interfaces\EmptyField $particularity) {
        parent::__construct($core, $limitation, $particularity);
        $this->structureElements = [$core, $limitation, $particularity];
        $this->structureConnections[] = $core->connect($limitation);
        $this->structureConnections[] = $core->connect($particularity);
    }


}