<?php 

namespace Circuit\Traits; 

trait HasDescription {
    /**
     * @property  string 
     */
    protected $description = '';

    public function getDescription() : string {
        return $this->description;
    }
}