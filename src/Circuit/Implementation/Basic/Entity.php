<?php

namespace Circuit\Implementation\Basic; 

use Circuit\Interfaces;
use Circuit\Traits\HasDescription;

class Entity implements Interfaces\Entity, Interfaces\Descriptable {

    use HasDescription;

    /**
     * @property  Interfaces\Core
     */
    protected $core;
    /**
     * @property  Interfaces\Limitation
     */
    protected $limitation;
    /**
     * @property  Interfaces\Particularity
     */
    protected $particularity;
    /**
     * @property  string 
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