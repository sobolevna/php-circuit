<?php 

namespace Circuit\Interfaces;

interface Process {

    public function finish(); 

    public function getState() : State;

    public function process(Handler $handler) : Process;
}