<?php 

namespace Circuit\Interfaces;

interface State {

    public function getPreviousState() : State;
}