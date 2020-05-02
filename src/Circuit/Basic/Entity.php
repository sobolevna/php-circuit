<?php

namespace Circuit\Basic; 

use Circuit\Interfaces;
use Circuit\Traits\HasDescription;

class Entity implements Interfaces\Entity, Interfaces\Descriptable {

    use HasDescription;

    /**
     * @var  Interfaces\Core
     */
    protected $core;
    /**
     * @var  Interfaces\Limitation
     */
    protected $limitation;
    /**
     * @var  Interfaces\Particularity
     */
    protected $particularity;
    /**
     * @var  string 
     */
    protected $description = 'Anything that is';

    public function __construct(Interfaces\Core $core, Interfaces\Limitation $limitation, Interfaces\Particularity $particularity) {
        $this->core = $core;
        $this->limitation = $limitation;
        $this->particularity = $particularity;
    }

    public function getCore() : Interfaces\Core {
        return $this->core;
    }

    public function getLimitation() : Interfaces\Limitation {
        return $this->limitation;
    }

    public function getParticularity() : Interfaces\Particularity {
        return $this->particularity;
    }
}