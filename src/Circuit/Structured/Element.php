<?php

namespace Circuit\Structured;

use Circuit\Interfaces;
use Circuit\Traits\IsElement;

class Element extends Entity implements Interfaces\Element{

    use IsElement;

    protected $description = 'Entity that is a structure. Core is a Node, limitation is EntryPoint, particularity is EmtpyField';

    protected $connectionClass = Connection::class;
}