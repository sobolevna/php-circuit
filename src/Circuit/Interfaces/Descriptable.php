<?php 

namespace Circuit\Interfaces;

interface Descriptable {

    public function getDescription() : string;

    public function setDescription(string $description);
}