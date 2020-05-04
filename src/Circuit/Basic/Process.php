<?php 

namespace Circuit\Basic;

use Circuit\{Interfaces, Traits};

class Process implements Interfaces\Process, Interfaces\Descriptable {

    use Traits\IsProcess, Traits\HasDescription;

    protected $description = 'Basic straightforward process';

    public function __construct($state) {
        $this->state = $state;
    }
}