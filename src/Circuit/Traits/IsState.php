<?php 

namespace Circuit\Traits; 

use Circuit\Interfaces\{State, Handler};
use Circuit\Exceptions\StateException;

trait IsState {
    /**
     * @var State 
     */
    protected $previousState;

    protected $properties = [];
    
    public function getPreviousState() : ?State {
        return $this->previousState;
    }

    public function setProperty($name, $value) {
        if ($this->immutable) {
            throw new StateException('Current state is immutable');
        }
        if (\property_exists($this, $name)) {
            $this->$name = $value;            
        }
        else {
            $this->properties[$name] = $value;
        }
    }

    public function getProperty($name, $default = null) {
        if (\property_exists($this, $name)) {
            return $this->$name ?? $default;            
        }
        else {
            return $this->properties[$name] ?? $default;
        }
    }

    public function setImmutable() : void {
        if (!$this->immutable) {
            $this->immutable = true;
        }
    }

    public function isImmutable() : bool {
        return !!$this->immutable;
    }

}