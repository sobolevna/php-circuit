<?php

namespace Circuit\Structured;

use Circuit\Interfaces;
use Circuit\Interfaces\Element;
use Circuit\Traits\IsElement;

class StructuredElement extends StructuredEntity implements Interfaces\Element{

    use IsElement;

    protected $description = 'Entity that is a structure. Core is a Node, limitation is EntryPoint, particularity is EmtpyField';

    protected $connectionClass = StructuredConnection::class;
}