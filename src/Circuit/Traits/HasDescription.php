<?php 

namespace Circuit\Traits; 

trait HasDescription {
    
    public function getDescription() : string {
        return $this->description;
    }

    public function setDescription(string $description) {
        $this->description = $description;
    }
}