<?php 

namespace Circuit\Interfaces;

interface Handler {

    public function handle(State $state) : State;
}