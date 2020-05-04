<?php 

namespace Circuit\Interfaces;

interface State {

    public function getPreviousState() : ?State;

    public function setImmutable() :void;

    public function isImmutable() : bool;
}