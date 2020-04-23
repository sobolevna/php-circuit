<?php 

namespace Circuit\Interfaces;

interface Connection {

    /**
     * @return Element[]
     */
    public function getElements() : array;
}