<?php 

namespace Circuit\Interfaces;

interface Entity {

    public function getLimitation() : Limitation;

    public function getCore() : Core; 

    public function getParticularity() : Particularity;
}