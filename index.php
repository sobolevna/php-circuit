<?php
require './vendor/autoload.php';

$builder = new Circuit\Implementation\Map\Builder(); 
$map = $builder->make(file_get_contents('./src/Circuit/examples/AP.json'));
var_dump($map->toJson());
